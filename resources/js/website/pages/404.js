const QUOTES = [
  'A lot of hard work is hidden behind nice things.',
  'I never said to myself, \'I’m going to be the greatest.\' I just wanted to do my own thing.',
  'The only thing necessary for the triumph of evil is for good men to do nothing.',
  'I surround myself with good people who make me feel great and give me positive energy.',
  'I surround myself with good people who make me feel great and give me positive energy.',
  'What you feel inside reflects on your face. So be happy and positive all the time.',
  'I surround myself with good people who make me feel great and give me positive energy.',
  'Don’t forget to tell yourself positive things daily! You must love yourself internally to glow externally.',
  'What you feel inside reflects on your face. So be happy and positive all the time.',
  'Smile from your heart; nothing is more beautiful than a woman who is happy to be herself.',
  'Whatever comes in my way, I take it with smile.',
  'Only I can change my life. No one can do it for me.',
  'Optimism is the faith that leads to achievement. Nothing can be done without hope and confidence.',
  'The secret of getting ahead is getting started.',
  'Good, better, best. Never let it rest. \'Til your good is better and your better is best.',
  'It does not matter how slowly you go as long as you do not stop.',
  'It’s not about ideas. It’s about making ideas happen.',
  'Always deliver more than expected.',
  'The most courageous act is still to think for yourself. Aloud.',
  'Nothing will work unless you do.',
  'Don’t be intimidated by what you don’t know. That can be your greatest strength and ensure that you do things differently from everyone else.',
  'Fearlessness is like a muscle. I know from my own life that the more I exercise it, the more natural it becomes to not let my fears run me.',
  'One does not discover new lands without consenting to lose sight of the shore for a very long time.',
  'Surround yourself with only people who are going to lift you higher.',
  'Sweating the details is more important than anything else.',
  'You shouldn’t blindly accept a leader’s advice. You’ve got to question leaders on occasion.',
  'Your time is limited, so don’t waste it living someone else’s life.',
  'Never give up. Today is hard, tomorrow will be worse, but the day after tomorrow will be sunshine.',
  'Define success on your own terms, achieve it by your own rules, and build a life you’re proud to live.',
  'Someone’s sitting in the shade today because someone planted a tree a long time ago.',
];

const INDEX = Math.floor(Math.random() * QUOTES.length);

(function appendInDocument() {
  document.querySelector('.quote').innerHTML = QUOTES[INDEX];
}());
