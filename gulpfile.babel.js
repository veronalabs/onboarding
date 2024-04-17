"use strict";

// This gulpfile makes use of new JavaScript features.
// Babel handles this without us having to do anything. It just works.
// You can read more about the new JavaScript features here:
// https://babeljs.io/docs/learn-es2015/

const { src, dest, parallel, series, watch } = require("gulp");
const del = require("del");
const babel = require("gulp-babel");
const browserSync = require("browser-sync").create();
var fs = require("fs");
var pkg = JSON.parse(fs.readFileSync("./package.json"));
const imagemin = require("gulp-imagemin");
const argv = require("yargs").argv;
const $ = require("gulp-load-plugins")();
const reload = browserSync.reload;
const isDemo = argv.build === "demo";
const terser = require("gulp-terser");
const sass = require("gulp-sass")(require("sass"));

sass.compiler = require("sass");
const zipFileName = `Veronalabs.com__${pkg.name}__v${pkg.version}`;

// Lint JavaScript
function lint() {
  return src(["src/scripts/**/*.js", "!node_modules/**"])
    .pipe($.eslint())
    .pipe($.eslint.format())
    .pipe($.if(!browserSync.active, $.eslint.failAfterError()));
}
exports.lint = lint;

// Optimize images
function images() {
  return src("src/img/**/*")
    .pipe(
      imagemin([
        imagemin.gifsicle({ interlaced: true }),
        imagemin.mozjpeg({ progressive: true }),
        imagemin.optipng({ optimizationLevel: 5 }),
        imagemin.svgo({
          plugins: [{ removeViewBox: true }, { cleanupIDs: false }],
        }),
      ])
    )
    .pipe(dest("dist/img"))
    .pipe($.size({ title: "img" }));
}
exports.images = images;

// Copy all files at the root level (src)
function copy() {
  return src(["src/*", "!src/*.html", "!src/images-placeholder"], {
    dot: true,
  })
    .pipe(dest("dist"))
    .pipe($.size({ title: "copy" }));
}
exports.copy = copy;

// Copy all files at the root level (src)
function fonts() {
  return src("src/fonts/**/*")
    .pipe(dest("dist/fonts/"))
    .pipe($.size({ title: "copy fonts" }));
}
exports.fonts = fonts;

// Copy all files at the root level (src)
function placeholder() {
  return src("src/images-placeholder/**/*")
    .pipe(dest("dist/img/"))
    .pipe($.size({ title: "copy fonts" }));
}

exports.placeholder = placeholder;

// Compile and automatically prefix stylesheets
function styles() {
  const AUTOPREFIXER_BROWSERS = [
    "ie >= 10",
    "ie_mob >= 10",
    "ff >= 30",
    "chrome >= 34",
    "safari >= 7",
    "opera >= 23",
    "ios >= 7",
    "android >= 4.4",
    "bb >= 10",
  ];

  // For best performance, don't add Sass partials to `gulp.src`
  return (
    src(["src/styles/**/*.scss", "src/styles/**/*.css","node_modules/select2/dist/css/select2.min.css" , "node_modules/tooltip-plugin/dist/tooltip.min.css","node_modules/datatables/media/css/jquery.dataTables.min.css"])
      .pipe($.newer(".tmp/styles"))
      .pipe(sass.sync({ outputStyle: "compressed" }).on("error", sass.logError))
      // .pipe(sass().on('error', sass.logError))
      .pipe($.autoprefixer(AUTOPREFIXER_BROWSERS))
      .pipe($.size({ title: "styles" }))
      .pipe($.concat("main.min.css"))
      .pipe(dest(".tmp/styles"))
      .pipe(dest("dist/styles"))
  );
}

exports.styles = styles;

// Concatenate and minify JavaScript. Optionally transpiles ES2015 code to ES5.
// to enable ES2015 support remove the line `'only': 'gulpfile.babel.js',` in the
// `.babelrc` file.
function scripts() {
  return (
    src([
      "./node_modules/select2/dist/js/select2.min.js",
      "./node_modules/datatables/media/js/jquery.dataTables.js",
      "./node_modules/tooltip-plugin/dist/tooltip.min.js",
      "./src/scripts/main.js",
      // Other scripts
    ])
      .pipe($.newer(".tmp/scripts"))
      .pipe($.sourcemaps.init())
      .pipe(
        babel({
          presets: ["@babel/env"],
        })
      )
      .pipe($.sourcemaps.write())
      .pipe(dest(".tmp/scripts"))
      .pipe($.concat("main.min.js"))
      .pipe(terser())
      // Output files
      .pipe($.size({ title: "scripts" }))
      .pipe(dest("dist/scripts"))
      .pipe(dest(".tmp/scripts"))
  );
}

exports.scripts = scripts;

// Scan your HTML for assets & optimize them
function html() {
  return (
    src("src/**/*.html")
      .pipe(
        $.useref({
          searchPath: [".", ".tmp", "src"],
        })
      )

      .pipe($.if("*.css", $.cssnano({ safe: true, autoprefixer: false })))
      .pipe($.if("*.js", terser()))

      // Minify any HTML
      .pipe(
        $.if(
          (file) => $.match(file, "*.html") && isDemo,
          $.htmlmin({
            removeComments: true,
            collapseWhitespace: true,
            collapseBooleanAttributes: true,
            removeAttributeQuotes: true,
            removeRedundantAttributes: true,
            removeEmptyAttributes: true,
            removeScriptTypeAttributes: true,
            removeStyleLinkTypeAttributes: true,
            removeOptionalTags: true,
          })
        )
      )
      // Output files
      .pipe($.if("*.html", $.size({ title: "html", showFiles: true })))
      .pipe(dest("dist"))
  );
}

exports.html = html;

// Clean output directory
function clean(cb) {
  del([".tmp", "dist/*", "!dist/.git"], { dot: true });
  cb();
}

exports.clean = clean;

function start() {
  browserSync.init({
    notify: false,
    // Customize the Browsersync console logging prefix
    logPrefix: "WSK",
    // Allow scroll syncing across breakpoints
    scrollElementMapping: ["main", ".mdl-layout"],
    server: {
      baseDir: [".tmp", "src"],
      routes: {
        "/node_modules": "node_modules",
      },
    },
    port: 3020,
  });

  watch("src/**/*.html").on("change", reload);
  watch("src/styles/**/*.{scss,css}", styles).on("change", reload);
  watch("src/scripts/**/*.js", parallel(lint, scripts)).on("change", reload);
  watch("src/img/**/*").on("change", reload);
}
exports.start = start;

exports.serve = series(parallel(styles, scripts), start);

// Build and serve the output from the dist build
function servedist() {
  browserSync.init({
    notify: false,
    logPrefix: "WSK",
    // Allow scroll syncing across breakpoints
    scrollElementMapping: ["main"],
     server: "dist",
    port: 3003,
  });
}

exports.servedist = servedist;

// Zip demo files
function zip() {
  return src("dist/**/*", { dot: true })
    .pipe($.zip(`${zipFileName}${isDemo ? "__DEMO" : ""}.zip`))
    .pipe(dest(".tmp"));
}

exports.zip = zip;

// Zip Release files
function ziprelease() {
  return src(".tmp/release/**/*", { dot: true })
    .pipe($.zip(`${zipFileName}.zip`))
    .pipe(dest(".tmp"));
}

exports.ziprelease = ziprelease;

// Build production files, the default task
exports.default = series(
  clean,
  styles,
  lint,
  html,
  scripts,
  images,
  copy,
  fonts,
  zip
);

/**
 * Generrate release commands
 * @return {Array} commands
 */
function getReleaseCommands() {
  const basePath = "./.tmp/release";

  const cp = [
    "dist",
    "src",
    ".babelrc",
    ".gitattributes",
    ".gitignore",
    "gulpfile.babel.prod.js",
    "package.prod.json",
    "README.md",
    "CHANGELOG.md",
    "LICENSE",
  ].map((p) => `cp -rf ./${p} ${basePath}/${p.replace(".prod", "")}`);

  return [
    `mkdir ${basePath}`,
    ...cp,
    `rm -rf ${basePath}/src/img`,
    `mv ${basePath}/src/images-placeholder ${basePath}/src/img`,
  ];
}

exports.getReleaseCommands = getReleaseCommands;
