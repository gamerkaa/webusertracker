var webuser;
var trackerphp;

function setTrackerPHP(url) { trackerphp = url; }
function getCurrentTimestamp() { return Math.floor(new Date().getTime() / 86400000); }

function tracker_saveevent(eventid, puser, passwd, ename, etype, emin, emax, edefault, fnsuccess, ...args) {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function(e) {
    if (xhr.status == 200 && xhr.readyState == 4) {
      fnsuccess(...args);
    } else if (xhr.status == 404 && xhr.readyState == 4) {
      console.log('Event ' + eventid + ' name=' + ename + ' type (string/integer/fraction)=' + etype + ' limits(' + emin + ',' + emax + ',' + edefault + ') save by user ' + puser + ' failed');
    }
  };

  xhr.open('POST', trackerphp, true);
  xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
  xhr.send('target=trackerevent&method=add&user=' + encodeURIComponent(puser) + '&pass=' + encodeURIComponent(passwd) + '&eventid=' + encodeURIComponent(eventid) + '&ename=' + encodeURIComponent(ename) + '&etype=' + encodeURIComponent(etype) + '&emin=' + encodeURIComponent(emin) + '&emax=' +  + encodeURIComponent(emax) + '&edefault=' + encodeURIComponent(edefault) + '&ts=' + encodeURIComponent(getCurrentTimestamp()));
}

function tracker_savedata(eventid, puser, pname, pvalue, fnsuccess, ...args) {
  var xhr = new XMLHttpRequest();
  var ts = getCurrentTimestamp();
  xhr.onreadystatechange = function(e) {
    if (xhr.status == 200 && xhr.readyState == 4) {
      fnsuccess(...args);
    } else if (xhr.status == 404 && xhr.readyState == 4) {
      console.log('Event ' + eventid + ' user=' + puser + ' name=' + pname + ' pvalue=' + pvalue + ' submission failed');
    }
  };

  xhr.open('POST', trackerphp, true);
  xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
  xhr.send('target=tracker&method=add&eventid=' + encodeURIComponent(eventid) + '&puser=' + encodeURIComponent(puser) + '&pname=' + encodeURIComponent(pname) + '&pvalue=' + encodeURIComponent(pvalue) + '&ts=' + encodeURIComponent(ts));
}

function tracker_loadevents(fnsuccess, ...args) {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function(e) {
    if (xhr.status == 200 && xhr.readyState == 4) {
      fnsuccess(xhr.responseText, ...args);
    } else if (xhr.status == 404 && xhr.readyState == 4) {
      console.log('tracker_loadevents failed');
    }
  };

  xhr.open('POST', trackerphp, true);
  xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
  xhr.send('target=trackerevent&method=list');
}

function tracker_loaddata(dayindex, fnsuccess, ...args) {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function(e) {
    if (xhr.status == 200 && xhr.readyState == 4) {
      fnsuccess(xhr.responseText, ...args);
    } else if (xhr.status == 404 && xhr.readyState == 4) {
      console.log('tracker_loaddata failed for ' + dayindex);
    }
  };

  xhr.open('POST', trackerphp, true);
  xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
  xhr.send('target=tracker&method=list&dayindex=' + dayindex);
}
