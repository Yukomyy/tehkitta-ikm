</main>
<script src="../script.js"></script>
<script>
function toggleSideUser(){var d=document.getElementById('sideUserDrop');d.style.display=d.style.display==='none'?'block':'none';}
function toggleTopUser(){var d=document.getElementById('topUserDrop');d.style.display=d.style.display==='none'?'block':'none';}
document.addEventListener('click',function(e){
  var s=document.getElementById('sideUserDrop'),t=document.getElementById('topUserDrop');
  if(s&&!e.target.closest('.user-card'))s.style.display='none';
  if(t&&!e.target.closest('.topbar-profile'))t.style.display='none';
});
setTimeout(function(){var t=document.getElementById('mainToast');if(t){t.style.transition='opacity .4s,transform .4s';t.style.opacity='0';t.style.transform='translateX(20px)';setTimeout(function(){t&&t.remove();},400);}},4000);
// Active nav
var path=window.location.pathname;
document.querySelectorAll('.nav-item,.nav-item').forEach(function(item){
  var href=(item.getAttribute('href')||'').replace('../','');
  if(href&&path.indexOf(href.replace('/index.php',''))>-1)item.classList.add('active');
});
</script>
</body></html>
