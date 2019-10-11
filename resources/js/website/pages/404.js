const QUOTES = [
  'A lot of hard work is hidden behind nice things.',
  'I never said to myself, \'I\'m going to be the greatest.\' I just wanted to do my own thing.',
  'The only thing necessary for the triumph of evil is for good men to do nothing.',
  'I surround myself with good people who make me feel great and give me positive energy.',
  'I surround myself with good people who make me feel great and give me positive energy.',
  'What you feel inside reflects on your face. So be happy and positive all the time.',
  'I surround myself with good people who make me feel great and give me positive energy.',
  'Don\'t forget to tell yourself positive things daily! You must love yourself internally to glow externally.',
  'What you feel inside reflects on your face. So be happy and positive all the time.',
  'Smile from your heart; nothing is more beautiful than a woman who is happy to be herself.',
  'Whatever comes in my way, I take it with smile.',
  'Only I can change my life. No one can do it for me.',
  'Optimism is the faith that leads to achievement. Nothing can be done without hope and confidence.',
  'The secret of getting ahead is getting started.',
  'Good, better, best. Never let it rest. \'Til your good is better and your better is best.',
  'It does not matter how slowly you go as long as you do not stop.',
];

const INDEX = Math.floor(Math.random() * QUOTES.length);

(function appendInDocument() {
  document.querySelector('.quote').innerHTML = QUOTES[INDEX];
}());
