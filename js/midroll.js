var currentPosition;
var currentState;
var featureFile     = 0;     // playlist position of the feature
var featureRepeat   = 5;     // amount of the feature to repeat when it re-starts
var midRollFile     = 1;     // playlist position of the midroll
var midRollPos      = 134;   // position into the feature to play the midroll
var midRollStart    = false; // has the midroll played yet?
var midRollFinished = false; // has the midroll finished playing yet?

function sendEvent(typ, prm)
{
  thisMovie('mpl').sendEvent(typ, prm);
  // instrumentation for debug
  evt = document.getElementById('event');
  evt.innerHTML = '???Event: ' + typ + ' ' + prm;
};

function getUpdate(typ, pr1, pr2, pid)
{
  if(typ == 'time')
  {
    currentPosition = pr1;
    if((!midRollStart) && (currentPosition > midRollPos) && (currentPosition < (midRollPos + 3)))
    {
      sendEvent('playitem', midRollFile);
      // remember that the midroll has been played
      midRollStart = true;
    }
    if((midRollStart) && (currentPosition > (midRollPos + 5)))
    {
      // reset the midRollStart & midRollFinished to play again after
      // 5 seconds of the main feature have played after the restart
      midRollStart    = false;
      midRollFinished = false;
    }
    // instrumentation for debug
    pos = document.getElementById('position');
    pos.innerHTML = 'Position: ' + currentPosition;
  }

  if(typ == 'state')
  {
    currentState = pr1;
    if((midRollStart) && (currentState == 3))
    {
      sendEvent('playitem', featureFile);             // reload the feature
      midRollFinished = true;
    }

    if((midRollFinished) && (currentState == 0))
    {
      sendEvent('playpause');                         // restart the feature
    }

    if((midRollFinished) && (currentState == 2))
    {
      sendEvent('scrub', midRollPos - featureRepeat); // scrub to the midroll point - the repeat time
    }

    // instrumentation for debug
    ste = document.getElementById('statee');
    ste.innerHTML = '????State: ' + currentState;
  }
};

function thisMovie(movieName)
{
  if(navigator.appName.indexOf("Microsoft") != -1)
  {
    return window[movieName];
  }
  else
  {
    return document[movieName];
  }
};
