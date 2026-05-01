var coll = document.getElementsByClassName("collapsible");

window.addEventListener('load', function() {
  if (sessionStorage.getItem('filterMenuOpen') === 'true') {
    var content = coll[0].nextElementSibling;
    coll[0].classList.add("active");
    content.style.maxHeight = content.scrollHeight + "px";
    var tempStyle = document.getElementById('temp-collapsible');
    if (tempStyle) tempStyle.remove();
  }
});

var resetBtn = document.getElementById("reset");
if (resetBtn) {
  resetBtn.addEventListener("click", function() {
    sessionStorage.setItem('filterMenuOpen', 'false');
  });
}

for (var i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
      sessionStorage.setItem('filterMenuOpen', 'false');
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
      sessionStorage.setItem('filterMenuOpen', 'true');
    }
  });
} 