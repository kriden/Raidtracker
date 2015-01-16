Array.prototype.remove = function(val) {
  var from = this.indexOf(val);
  var to = from + 1.
  var rest = this.slice((to || from) + 1 || this.length);
  
  if(this.length > 0) {
    this.length = from < 0 ? this.length + from : from;
    return this.push.apply(this, rest);
  }
  return this;
};

Array.prototype.contains = function(obj) {
    var i = this.length;
    while (i--) {
        if (this[i] === obj) {
            return true;
        }
    }
    return false;
}


function capitalize(string) {
    return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
}

Chart.defaults.global.colours = [
{ // orange
  fillColor: "rgba(255,171,0,.2)",
  strokeColor: "rgba(255,171,0,1)",
  pointColor: "rgba(255,171,0,1)",
  pointStrokeColor: "#fff",
  pointHighlightFill: "#fff",
  pointHighlightStroke: "rgba(255,171,0.8)"
},
{ // yellow
      fillColor: "rgba(253,180,92,.2)",
      strokeColor: "rgba(253,180,92,1)",
      pointColor: "rgba(253,180,92,1)",
      pointStrokeColor: "#fff",
      pointHighlightFill: "#fff",
      pointHighlightStroke: "rgba(253,180,92,0.8)"
    },
{ // red
  fillColor: "rgba(247,70,74,0)",
  strokeColor: "rgba(247,70,74,1)",
  pointColor: "rgba(247,70,74,1)",
  pointStrokeColor: "#fff",
  pointHighlightFill: "#fff",
  pointHighlightStroke: "rgba(247,70,74,0.8)"
}];

Chart.defaults.global.classColours = [

  { // Warrior
    fillColor: "rgba(199,156,110,1)",
    highlightColor: "rgba(199,156,110,1)",
    strokeColor: "#FFF",
    highlightStroke: "#000"
  },
  { // Paladin
    fillColor: "rgba(245,140,186,1)",
    highlightColor: "rgba(245,140,186,1)",
    strokeColor: "#FFF",
    highlightStroke: "#000"
  },
  { // Hunter
    fillColor: "rgba(171,212,115,1)",
    highlightColor: "rgba(171,212,115,1)",
    strokeColor: "#FFF",
    highlightStroke: "#000"
  },
  { // Rogue
    fillColor: "rgba(255,245,105,1)",
    highlightColor: "rgba(255,245,105,1)",
    strokeColor: "#FFF",
    highlightStroke: "#000"
  },
  { // Priest
    fillColor: "rgba(255,255,255,1)",
    highlightColor: "rgba(255,255,255,1)",
    strokeColor: "#FFF",
    highlightStroke: "#000"
  },
  { // Death Knight
    fillColor: "rgba(196,31,59,1)",
    highlightColor: "rgba(196,31,59,1)",
    strokeColor: "#FFF",
    highlightStroke: "#000"
  },
  { // Shaman
    fillColor: "rgba(0,112,222,1)",
    highlightColor: "rgba(0,112,222,1)",
    strokeColor: "#FFF",
    highlightStroke: "#000"
  },
  { // Mage
    fillColor: "rgba(105,204,240,1)",
    highlightColor: "rgba(105,204,240,1)",
    strokeColor: "#FFF",
    highlightStroke: "#000"
  },
  { // Warlock
    fillColor: "rgba(148,130,201,1)",
    highlightColor: "rgba(148,130,201,1)",
    strokeColor: "#FFF",
    highlightStroke: "#000"
  },
  { // Monk
    fillColor: "rgba(0,255,150,1)",
    highlightColor: "rgba(0,255,150,1)",
    strokeColor: "#FFF",
    highlightStroke: "#000"
  },
  { // Druid
    fillColor: "rgba(255,125,10,1)",
    highlightColor: "rgba(255,125,10,1)",
    strokeColor: "#FFF",
    highlightStroke: "#000"
  }
  ];