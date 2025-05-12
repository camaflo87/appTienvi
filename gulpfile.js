// Generar package.json : npm init
// Agregar package.json antes de los scrips: "type": "module",
// Instalar Sass: npm i -D Sass
// Instalar Gulp: npm install gulp@latest --save-dev
// Instalar terser:  npm i --save-dev gulp-terser
// Instalar glob: npm i --save-dev glob
// Instalar sharp: npm i --save-dev sharp
// Instalar webpackStream: npm install --save-dev webpack-stream

import path from "path";
import fs from "fs";
import { glob } from "glob";
import { src, dest, watch, parallel } from "gulp";
import * as dartSass from "sass";
import gulpSass from "gulp-sass";
import webpackStream from "webpack-stream";
const sass = gulpSass(dartSass);

// CSS
// import plumber from "gulp-plumber";
import autoprefixer from "autoprefixer";
import cssnano from "cssnano";
import postcss from "gulp-postcss";
import sourcemaps from "gulp-sourcemaps";

// Imagenes
import sharp from "sharp";

// Javascript
import terser from "gulp-terser";
import concat from "gulp-concat";
import rename from "gulp-rename";

const paths = {
  scss: "src/scss/**/*.scss",
  js: "src/js/**/*.js",
  imagenes: "src/img/**/*.{png,jpg}",
};

export function css() {
  return src(paths.scss)
    .pipe(sourcemaps.init())
    .pipe(sass({ outputStyle: "compressed" }))
    .pipe(postcss([autoprefixer(), cssnano()]))
    .pipe(sourcemaps.write("."))
    .pipe(dest("public/build/css"));
}

export async function crop(done) {
  const inputFolder = "src/img/gallery/full";
  const outputFolder = "src/img/gallery/thumb";
  const width = 250;
  const height = 180;
  if (!fs.existsSync(outputFolder)) {
    fs.mkdirSync(outputFolder, { recursive: true });
  }
  const images = fs.readdirSync(inputFolder).filter((file) => {
    return /\.(jpg)$/i.test(path.extname(file));
  });
  try {
    images.forEach((file) => {
      const inputFile = path.join(inputFolder, file);
      const outputFile = path.join(outputFolder, file);
      sharp(inputFile)
        .resize(width, height, {
          position: "centre",
        })
        .toFile(outputFile);
    });

    done();
  } catch (error) {
    console.log(error);
  }
}

export async function imagenes(done) {
  const srcDir = "./src/img";
  const buildDir = "./public/build/img";
  const images = await glob("./src/img/**/*{jpg,png}");

  images.forEach((file) => {
    const relativePath = path.relative(srcDir, path.dirname(file));
    const outputSubDir = path.join(buildDir, relativePath);
    procesarImagenes(file, outputSubDir);
  });
  done();
}

function procesarImagenes(file, outputSubDir) {
  if (!fs.existsSync(outputSubDir)) {
    fs.mkdirSync(outputSubDir, { recursive: true });
  }
  const baseName = path.basename(file, path.extname(file));
  const extName = path.extname(file);
  const outputFile = path.join(outputSubDir, `${baseName}${extName}`);
  const outputFileWebp = path.join(outputSubDir, `${baseName}.webp`);
  const outputFileAvif = path.join(outputSubDir, `${baseName}.avif`);

  const options = { quality: 80 };
  sharp(file).jpeg(options).toFile(outputFile);
  sharp(file).webp(options).toFile(outputFileWebp);
  sharp(file).avif().toFile(outputFileAvif);
}

export function javascript() {
  return src(paths.js)
    .pipe(
      webpackStream({
        mode: "production",
        entry: "./src/js/app.js",
      })
    )
    .pipe(sourcemaps.init())
    .pipe(concat("bundle.js"))
    .pipe(terser())
    .pipe(sourcemaps.write("."))
    .pipe(rename({ suffix: ".min" }))
    .pipe(dest("./public/build/js"));
}

export function dev() {
  watch(paths.scss, css);
  watch(paths.js, javascript);
  watch(paths.imagenes, imagenes);
}

export default parallel(crop, javascript, css, imagenes, dev);
