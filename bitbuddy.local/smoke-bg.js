/**
 * BitBuddy Smoke Background Effect
 * WebGL2 fragment shader smoke animation with blue tint
 * Adapts to both dark and light themes
 */

(function() {
    'use strict';

    const fragmentShaderSource = `#version 300 es
precision highp float;
out vec4 O;
uniform float time;
uniform vec2 resolution;
uniform vec3 u_color;
uniform float u_light;

#define FC gl_FragCoord.xy
#define R resolution
#define T (time+660.)

float rnd(vec2 p){p=fract(p*vec2(12.9898,78.233));p+=dot(p,p+34.56);return fract(p.x*p.y);}
float noise(vec2 p){vec2 i=floor(p),f=fract(p),u=f*f*(3.-2.*f);return mix(mix(rnd(i),rnd(i+vec2(1,0)),u.x),mix(rnd(i+vec2(0,1)),rnd(i+1.),u.x),u.y);}
float fbm(vec2 p){float t=.0,a=1.;for(int i=0;i<5;i++){t+=a*noise(p);p*=mat2(1,-1.2,.2,1.2)*2.;a*=.5;}return t;}

void main(){
  vec2 uv=(FC-.5*R)/R.y;
  vec3 col=vec3(1);
  uv.x+=.25;
  uv*=vec2(2,1);

  float n=fbm(uv*.28-vec2(T*.01,0));
  n=noise(uv*3.+n*2.);

  col.r-=fbm(uv+vec2(0,T*.015)+n);
  col.g-=fbm(uv*1.003+vec2(0,T*.015)+n+.003);
  col.b-=fbm(uv*1.006+vec2(0,T*.015)+n+.006);

  col=mix(col, u_color, dot(col,vec3(.21,.71,.07)));

  // Dark mode: fade in from black
  vec3 darkBase=mix(vec3(.08),col,min(time*.1,1.));
  darkBase=clamp(darkBase,.08,1.);

  // Light mode: white background, smoke adds a clearly visible blue tint
  // smokeMask near 0 = lots of smoke, near 1 = clear
  float smokeMask = dot(col, vec3(0.21, 0.71, 0.07));
  // Push contrast further so the smoke reads without making the page heavy
  float smokeStrength = pow(1.0 - smokeMask, 1.4);
  vec3 lightBase = mix(
    vec3(1.0, 1.0, 1.0),
    vec3(0.45, 0.74, 1.0),
    smokeStrength * 0.55
  );
  lightBase = clamp(lightBase, 0.0, 1.0);

  col=mix(darkBase,lightBase,u_light);
  O=vec4(col,1);
}`;

    const vertexSrc = "#version 300 es\nprecision highp float;\nin vec4 position;\nvoid main(){gl_Position=position;}";
    const vertices = [-1, 1, -1, -1, 1, 1, 1, -1];

    const darkColor = [0.412, 0.855, 1.0];
    const lightColor = [0.18, 0.62, 1.0];
    let smokeColor = darkColor;
    let isLight = false;
    let canvas = null;

    function applyThemeToCanvas() {
        if (!canvas) return;
        isLight = (document.documentElement.getAttribute('data-theme') || 'dark') === 'light';
        smokeColor = isLight ? lightColor : darkColor;
    }

    // Listen for theme changes via custom event and MutationObserver
    window.addEventListener('themechange', function() {
        applyThemeToCanvas();
    });

    // Also watch data-theme attribute directly via MutationObserver
    const themeObserver = new MutationObserver(function(mutations) {
        mutations.forEach(function(m) {
            if (m.attributeName === 'data-theme') {
                applyThemeToCanvas();
            }
        });
    });
    themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });

    function initSmokeBackground() {
        canvas = document.getElementById('smoke-canvas');
        if (!canvas) return;

        applyThemeToCanvas();

        const gl = canvas.getContext('webgl2');
        if (!gl) {
            console.warn('WebGL2 not supported, smoke background disabled');
            return;
        }

        function compileShader(type, source) {
            const shader = gl.createShader(type);
            gl.shaderSource(shader, source);
            gl.compileShader(shader);
            if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
                console.error('Shader error:', gl.getShaderInfoLog(shader));
                gl.deleteShader(shader);
                return null;
            }
            return shader;
        }

        const vs = compileShader(gl.VERTEX_SHADER, vertexSrc);
        const fs = compileShader(gl.FRAGMENT_SHADER, fragmentShaderSource);
        if (!vs || !fs) return;

        const program = gl.createProgram();
        gl.attachShader(program, vs);
        gl.attachShader(program, fs);
        gl.linkProgram(program);

        if (!gl.getProgramParameter(program, gl.LINK_STATUS)) {
            console.error('Program link error:', gl.getProgramInfoLog(program));
            return;
        }

        const buffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, buffer);
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertices), gl.STATIC_DRAW);

        const positionLoc = gl.getAttribLocation(program, 'position');
        gl.enableVertexAttribArray(positionLoc);
        gl.vertexAttribPointer(positionLoc, 2, gl.FLOAT, false, 0, 0);

        const resolutionLoc = gl.getUniformLocation(program, 'resolution');
        const timeLoc = gl.getUniformLocation(program, 'time');
        const colorLoc = gl.getUniformLocation(program, 'u_color');
        const lightLoc = gl.getUniformLocation(program, 'u_light');

        function updateSize() {
            const parent = canvas.parentElement;
            if (!parent) return;
            const dpr = Math.max(1, window.devicePixelRatio);
            const rect = parent.getBoundingClientRect();
            canvas.width = rect.width * dpr;
            canvas.height = rect.height * dpr;
            canvas.style.width = rect.width + 'px';
            canvas.style.height = rect.height + 'px';
            gl.viewport(0, 0, canvas.width, canvas.height);
        }
        updateSize();
        window.addEventListener('resize', updateSize);

        function render(now) {
            if (isLight) {
                gl.clearColor(1.0, 1.0, 1.0, 1);
            } else {
                gl.clearColor(0, 0, 0, 1);
            }
            gl.clear(gl.COLOR_BUFFER_BIT);
            gl.useProgram(program);
            gl.bindBuffer(gl.ARRAY_BUFFER, buffer);
            gl.uniform2f(resolutionLoc, canvas.width, canvas.height);
            gl.uniform1f(timeLoc, now * 1e-3);
            gl.uniform3fv(colorLoc, smokeColor);
            gl.uniform1f(lightLoc, isLight ? 1.0 : 0.0);
            gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
            requestAnimationFrame(render);
        }
        requestAnimationFrame(render);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSmokeBackground);
    } else {
        initSmokeBackground();
    }
})();
