const { exec } = require('child_process');

const args = process.argv;
process.argv.splice(0, 2);
const str = args.join(' ');

function cb(err) {
  return err ? console.log(err) : null;
}

exec('git add .', cb);
exec(`git commit -m "${str}"`, cb);
