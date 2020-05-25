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

function cbcommit(err, info1, info2) {
  if (err) {
    console.log(err);
    return;
  }
  console.log(info2, info1);
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
