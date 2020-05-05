const { exec } = require('child_process');

const args = process.argv;
process.argv.splice(0, 2);
const str = args.join(' ');

function cbpush(err) {
  if (err) {
    console.log(err);
    return;
  }
  console.log('done');
}

function cbcommit(err) {
  if (err) {
    console.log(err);
    return;
  }
  exec('git push', cbpush);
}

function cbadd(err) {
  if (err) {
    console.log(err);
    return;
  }
  exec(`git commit -m "${str}"`, cbcommit);
}

exec('git add .', cbadd);
