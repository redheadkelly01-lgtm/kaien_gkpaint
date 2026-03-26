const fs = require('fs');
let html = fs.readFileSync('index3.html', 'utf8');

const cssMatch = html.match(/<style>\s*([\s\S]*?)<\/style>/);
if (cssMatch) fs.writeFileSync('css/style.css', cssMatch[1].trim());

html = html.replace(/<style>[\s\S]*?<\/style>/, '<link rel="stylesheet" href="css/style.css">');

html = html.replace(/<script>\s*\/\/ index2\.html 用の拡縮ロジック[\s\S]*?<\/script>\s*/, '');
html = html.replace(/<script>\s*document\.getElementById\('contactForm'\)[\s\S]*?<\/script>\s*/, '<script src="js/main.js" defer></script>\n');

fs.writeFileSync('index2.html', html);
console.log('Extraction complete');
