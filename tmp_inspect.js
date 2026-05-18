const fs = require('fs');
const path = 'generar_pdf_venta.php';
const data = fs.readFileSync(path);
console.log('size', data.length);
console.log('start bytes', data.slice(0, 20).toString('hex'));
console.log('contains BOM', data.slice(0, 3).equals(Buffer.from([0xef, 0xbb, 0xbf])));
console.log('first200', data.slice(0, 200).toString('utf8'));
