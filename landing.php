<?php
/**
 * LANDING PAGE — Teh Kitta
 * Company profile & kemitraan. Tetap satu project dengan sistem POS (PHP).
 * Branding: hijau, putih, aksen kuning & merah. Tidak ada ilustrasi AI/robot.
 */
if (session_status() === PHP_SESSION_NONE) session_start();
$is_logged_in = !empty($_SESSION['user_id']);
$dash_link = $is_logged_in ? 'dashboard/index.php' : 'auth/login.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Teh Kitta — Teh Asli Indonesia · Peluang Kemitraan</title>
<meta name="description" content="Teh Kitta — jaringan kemitraan minuman teh segar asli Indonesia. Modal terjangkau, sistem terintegrasi, dukungan operasional penuh untuk setiap mitra.">
<link rel="icon" href="logo.png">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,400&display=swap" rel="stylesheet">
<style>
/* ════════ TOKENS ════════ */
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
:root{
  --green-50:#f0faf2; --green-100:#d8f3de; --green-200:#aee5ba; --green-300:#78d08a;
  --green-400:#4aba5e; --green-500:#2da042; --green-600:#228234; --green-700:#1a6328;
  --green-800:#124a1e;
  --leaf:#3fae5a;
  --amber:#f5b301; --amber-dark:#e0a000; --amber-soft:#fff7e0;
  --red:#e23b3b; --red-dark:#c92b2b; --red-soft:#fdecec;
  --ink-900:#10231a; --ink-800:#19342a; --ink-700:#28453a; --ink-500:#5a6f64;
  --ink-400:#8298a0; --paper:#fbfdfb; --white:#ffffff;
  --line:#e6efe8;
  --shadow-sm:0 1px 3px rgba(16,35,26,.06),0 4px 12px rgba(16,35,26,.04);
  --shadow-md:0 8px 30px rgba(16,35,26,.08),0 2px 8px rgba(16,35,26,.04);
  --shadow-lg:0 24px 60px rgba(16,35,26,.12),0 8px 24px rgba(16,35,26,.07);
  --shadow-green:0 14px 34px rgba(34,130,52,.28);
  --r-sm:10px; --r-md:16px; --r-lg:24px; --r-xl:32px; --r-full:999px;
  --nav-h:72px;
}
[data-theme="dark"]{
  --paper:#0d1712; --white:#13211b; --line:#1f3329;
  --ink-900:#eef6f0; --ink-800:#dcebe0; --ink-700:#bcd0c2; --ink-500:#8ba596;
  --ink-400:#6b8678;
  --green-50:#13251a; --green-100:#193524;
  --shadow-sm:0 1px 3px rgba(0,0,0,.4); --shadow-md:0 8px 30px rgba(0,0,0,.45);
  --shadow-lg:0 24px 60px rgba(0,0,0,.55);
}
html{scroll-behavior:smooth;-webkit-text-size-adjust:100%;}
body{
  font-family:'Plus Jakarta Sans',sans-serif;
  background:var(--paper); color:var(--ink-700);
  line-height:1.65; overflow-x:hidden;
  transition:background .4s ease,color .4s ease;
}
h1,h2,h3,h4{color:var(--ink-900);line-height:1.12;letter-spacing:-.02em;font-weight:800;}
.serif{font-family:'Fraunces',serif;font-weight:500;letter-spacing:-.01em;}
img{max-width:100%;display:block;}
a{color:inherit;text-decoration:none;}
section{position:relative;}
.wrap{max-width:1200px;margin:0 auto;padding:0 28px;}
.center{text-align:center;}

/* ════════ PRELOADER ════════ */
#preloader{
  position:fixed;inset:0;z-index:9999;background:var(--green-700);
  display:flex;flex-direction:column;align-items:center;justify-content:center;gap:22px;
  transition:opacity .6s ease,visibility .6s ease;
}
#preloader.done{opacity:0;visibility:hidden;}
.pl-cup{position:relative;width:62px;height:78px;}
.pl-glass{
  position:absolute;inset:0;border:3px solid rgba(255,255,255,.45);
  border-radius:6px 6px 16px 16px;overflow:hidden;background:rgba(255,255,255,.05);
}
.pl-tea{position:absolute;left:0;right:0;bottom:0;height:0;background:linear-gradient(180deg,#c9802f,#8a4f1c);animation:fill 1.5s ease-in-out infinite;}
@keyframes fill{0%{height:8%}50%{height:80%}100%{height:8%}}
.pl-text{color:#fff;font-family:'Fraunces',serif;font-size:1.3rem;letter-spacing:.5px;}
.pl-text b{font-weight:600;}

/* ════════ NAVBAR ════════ */
.nav{
  position:fixed;top:0;left:0;right:0;height:var(--nav-h);z-index:1000;
  display:flex;align-items:center;
  background:transparent;transition:all .35s ease;
}
.nav.scrolled{
  height:64px;background:color-mix(in srgb,var(--white) 82%,transparent);
  backdrop-filter:blur(18px) saturate(160%);
  border-bottom:1px solid var(--line);box-shadow:var(--shadow-sm);
}
.nav-inner{max-width:1200px;margin:0 auto;padding:0 28px;width:100%;display:flex;align-items:center;gap:32px;}
.brand{display:flex;align-items:center;gap:11px;flex-shrink:0;}
.brand img{width:42px;height:42px;border-radius:50%;box-shadow:0 3px 10px rgba(26,99,40,.25);}
.brand-name{font-weight:800;font-size:1.18rem;color:var(--ink-900);letter-spacing:-.4px;line-height:1;}
.brand-name span{color:var(--green-500);}
.brand-tag{font-size:.62rem;letter-spacing:1.5px;text-transform:uppercase;color:var(--ink-400);font-weight:600;margin-top:2px;}
.nav.scrolled .brand-name,.nav.scrolled .brand-tag{color:var(--ink-900);}
.nav-links{display:flex;align-items:center;gap:4px;margin-left:auto;}
.nav-link{
  padding:9px 15px;border-radius:var(--r-full);font-size:.86rem;font-weight:600;
  color:var(--ink-700);transition:all .2s;position:relative;
}
.nav:not(.scrolled) .nav-link{color:rgba(255,255,255,.9);}
.nav-link:hover{background:color-mix(in srgb,var(--green-500) 12%,transparent);color:var(--green-600);}
.nav:not(.scrolled) .nav-link:hover{background:rgba(255,255,255,.16);color:#fff;}
.nav-cta{
  display:inline-flex;align-items:center;gap:7px;padding:10px 20px;border-radius:var(--r-full);
  background:var(--green-500);color:#fff;font-weight:700;font-size:.86rem;
  box-shadow:0 6px 18px rgba(45,160,66,.35);transition:all .25s;border:none;cursor:pointer;
}
.nav-cta:hover{background:var(--green-600);transform:translateY(-2px);box-shadow:0 10px 26px rgba(45,160,66,.45);}
.nav-tools{display:flex;align-items:center;gap:8px;}
.icon-toggle{
  width:40px;height:40px;border-radius:50%;border:1px solid var(--line);
  background:var(--white);display:flex;align-items:center;justify-content:center;
  cursor:pointer;color:var(--ink-700);transition:all .25s;flex-shrink:0;
}
.nav:not(.scrolled) .icon-toggle{background:rgba(255,255,255,.14);border-color:rgba(255,255,255,.25);color:#fff;}
.icon-toggle:hover{transform:translateY(-2px);box-shadow:var(--shadow-sm);border-color:var(--green-300);}
.icon-toggle svg{width:18px;height:18px;}
.hamburger{display:none;}

/* mobile drawer */
.drawer{
  position:fixed;inset:0;z-index:1100;pointer-events:none;
}
.drawer-bg{position:absolute;inset:0;background:rgba(16,35,26,.5);opacity:0;transition:opacity .3s;}
.drawer-panel{
  position:absolute;top:0;right:0;bottom:0;width:min(82vw,330px);background:var(--white);
  box-shadow:var(--shadow-lg);transform:translateX(100%);transition:transform .35s cubic-bezier(.4,0,.2,1);
  padding:26px 24px;display:flex;flex-direction:column;gap:6px;
}
.drawer.open{pointer-events:auto;}
.drawer.open .drawer-bg{opacity:1;}
.drawer.open .drawer-panel{transform:translateX(0);}
.drawer-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;}
.drawer-link{padding:13px 14px;border-radius:var(--r-sm);font-weight:600;color:var(--ink-700);font-size:.95rem;transition:all .2s;}
.drawer-link:hover{background:var(--green-50);color:var(--green-600);}

/* ════════ HERO ════════ */
.hero{
  min-height:100vh;display:flex;align-items:center;position:relative;overflow:hidden;
  background:
    radial-gradient(ellipse 80% 60% at 75% 20%,rgba(74,186,94,.18),transparent 60%),
    linear-gradient(160deg,var(--green-700) 0%,var(--green-600) 42%,var(--green-500) 100%);
  padding-top:var(--nav-h);
}
.hero::before{
  content:'';position:absolute;inset:0;
  background-image:radial-gradient(rgba(255,255,255,.05) 1px,transparent 1px);
  background-size:26px 26px;opacity:.6;
}
.hero-leaf{position:absolute;border-radius:0 100% 0 100%;background:rgba(255,255,255,.04);pointer-events:none;}
.hero-leaf.l1{width:340px;height:340px;top:-90px;right:-60px;transform:rotate(20deg);}
.hero-leaf.l2{width:220px;height:220px;bottom:-70px;left:-50px;transform:rotate(-30deg);background:rgba(245,179,1,.06);}
.hero-grid{
  max-width:1200px;margin:0 auto;padding:0 28px;width:100%;
  display:grid;grid-template-columns:1.05fr .95fr;gap:48px;align-items:center;
  position:relative;z-index:2;
}
.hero-eyebrow{
  display:inline-flex;align-items:center;gap:9px;padding:7px 16px;border-radius:var(--r-full);
  background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.22);
  color:#fff;font-size:.78rem;font-weight:600;letter-spacing:.3px;backdrop-filter:blur(6px);
  margin-bottom:24px;
}
.hero-eyebrow .dot{width:7px;height:7px;border-radius:50%;background:var(--amber);box-shadow:0 0 0 4px rgba(245,179,1,.25);}
.hero h1{font-size:clamp(2.6rem,5.4vw,4.3rem);color:#fff;margin-bottom:22px;}
.hero h1 .accent{font-family:'Fraunces',serif;font-weight:400;font-style:italic;color:var(--amber);}
.hero-sub{font-size:clamp(1rem,1.6vw,1.18rem);color:rgba(255,255,255,.85);max-width:520px;margin-bottom:36px;line-height:1.7;}
.hero-btns{display:flex;gap:14px;flex-wrap:wrap;margin-bottom:42px;}
.btn-amber{
  display:inline-flex;align-items:center;gap:9px;padding:15px 30px;border-radius:var(--r-full);
  background:var(--amber);color:var(--ink-900);font-weight:800;font-size:.98rem;border:none;cursor:pointer;
  box-shadow:0 12px 30px rgba(245,179,1,.4);transition:all .25s;
}
.btn-amber:hover{transform:translateY(-3px);box-shadow:0 18px 40px rgba(245,179,1,.5);background:#ffc01f;}
.btn-glass{
  display:inline-flex;align-items:center;gap:9px;padding:15px 28px;border-radius:var(--r-full);
  background:rgba(255,255,255,.1);color:#fff;font-weight:700;font-size:.98rem;cursor:pointer;
  border:1.5px solid rgba(255,255,255,.3);backdrop-filter:blur(8px);transition:all .25s;
}
.btn-glass:hover{background:rgba(255,255,255,.2);transform:translateY(-3px);}
.hero-trust{display:flex;gap:30px;flex-wrap:wrap;}
.hero-trust .t{color:#fff;}
.hero-trust .t b{display:block;font-family:'Fraunces',serif;font-size:1.8rem;font-weight:600;line-height:1;}
.hero-trust .t span{font-size:.76rem;color:rgba(255,255,255,.7);letter-spacing:.3px;}

/* hero visual — CSS illustration of a teh cup */
.hero-visual{position:relative;display:flex;align-items:center;justify-content:center;min-height:480px;}
.cup-card{
  position:relative;width:330px;height:430px;border-radius:36px;
  background:linear-gradient(165deg,rgba(255,255,255,.16),rgba(255,255,255,.04));
  border:1px solid rgba(255,255,255,.25);backdrop-filter:blur(14px);
  box-shadow:0 30px 70px rgba(0,0,0,.3);
  display:flex;align-items:flex-end;justify-content:center;overflow:hidden;
}
.cup-card::before{content:'';position:absolute;top:-40%;left:-30%;width:160%;height:80%;background:radial-gradient(circle,rgba(255,255,255,.22),transparent 65%);}
.cup{position:relative;width:170px;height:230px;margin-bottom:42px;animation:floaty 5s ease-in-out infinite;}
@keyframes floaty{0%,100%{transform:translateY(0)}50%{transform:translateY(-14px)}}
.cup-body{
  position:absolute;bottom:0;left:50%;transform:translateX(-50%);
  width:128px;height:188px;background:linear-gradient(180deg,rgba(255,255,255,.55),rgba(255,255,255,.32));
  border:2.5px solid rgba(255,255,255,.7);border-radius:8px 8px 22px 22px;overflow:hidden;
  box-shadow:inset 0 0 22px rgba(255,255,255,.4);
}
.cup-tea{position:absolute;bottom:0;left:0;right:0;height:72%;
  background:linear-gradient(180deg,#d68a35 0%,#b56a22 55%,#8f4e16 100%);}
.cup-tea::after{content:'';position:absolute;top:0;left:0;right:0;height:9px;background:rgba(255,255,255,.3);}
.ice{position:absolute;background:rgba(255,255,255,.55);border:1px solid rgba(255,255,255,.7);border-radius:5px;}
.ice.i1{width:30px;height:28px;top:38%;left:18%;transform:rotate(18deg);}
.ice.i2{width:26px;height:24px;top:46%;right:16%;transform:rotate(-14deg);}
.ice.i3{width:24px;height:22px;top:58%;left:38%;transform:rotate(8deg);}
.straw{position:absolute;top:-26px;left:54%;width:13px;height:150px;background:linear-gradient(90deg,var(--red),var(--red-dark));border-radius:7px;transform:rotate(11deg);box-shadow:0 2px 6px rgba(0,0,0,.2);}
.cup-lid{position:absolute;top:-12px;left:50%;transform:translateX(-50%);width:140px;height:20px;background:rgba(255,255,255,.85);border-radius:7px;box-shadow:0 3px 8px rgba(0,0,0,.15);}
.cup-badge{
  position:absolute;bottom:54px;left:50%;transform:translateX(-50%);z-index:3;
  width:62px;height:62px;border-radius:50%;background:var(--green-600);border:3px solid #fff;
  display:flex;align-items:center;justify-content:center;box-shadow:0 6px 16px rgba(0,0,0,.25);
}
.cup-badge img{width:46px;height:46px;border-radius:50%;}
.bubble{position:absolute;border-radius:50%;background:rgba(255,255,255,.6);animation:rise 4s ease-in infinite;}
@keyframes rise{0%{transform:translateY(0);opacity:0}30%{opacity:.8}100%{transform:translateY(-90px);opacity:0}}
.float-chip{
  position:absolute;background:var(--white);border-radius:16px;padding:12px 16px;box-shadow:var(--shadow-lg);
  display:flex;align-items:center;gap:11px;z-index:5;border:1px solid var(--line);
}
.float-chip .fc-ic{width:38px;height:38px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.float-chip .fc-ic svg{width:20px;height:20px;}
.float-chip b{font-size:.92rem;color:var(--ink-900);display:block;line-height:1.1;}
.float-chip span{font-size:.72rem;color:var(--ink-400);}
.fc-1{top:24px;left:-30px;animation:floaty 6s ease-in-out infinite;}
.fc-2{bottom:50px;right:-26px;animation:floaty 6s ease-in-out .8s infinite;}

/* ════════ WAVE DIVIDER ════════ */
.wave{display:block;width:100%;height:auto;line-height:0;}
.wave svg{display:block;width:100%;height:70px;}

/* ════════ SECTION SHELL ════════ */
.sec{padding:96px 0;}
.sec-tag{
  display:inline-flex;align-items:center;gap:8px;padding:6px 15px;border-radius:var(--r-full);
  background:var(--green-50);color:var(--green-600);font-size:.76rem;font-weight:700;
  letter-spacing:.6px;text-transform:uppercase;margin-bottom:16px;border:1px solid var(--green-100);
}
.sec-title{font-size:clamp(1.9rem,3.4vw,2.7rem);margin-bottom:14px;}
.sec-title em{font-family:'Fraunces',serif;font-style:italic;font-weight:400;color:var(--green-500);}
.sec-lead{font-size:1.04rem;color:var(--ink-500);max-width:600px;margin:0 auto;line-height:1.7;}

/* reveal */
.reveal{opacity:0;transform:translateY(34px);transition:opacity .7s cubic-bezier(.16,1,.3,1),transform .7s cubic-bezier(.16,1,.3,1);}
.reveal.in{opacity:1;transform:none;}
.reveal.d1{transition-delay:.08s}.reveal.d2{transition-delay:.16s}.reveal.d3{transition-delay:.24s}.reveal.d4{transition-delay:.32s}

/* ════════ TENTANG ════════ */
.about-grid{display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;}
.about-visual{position:relative;border-radius:var(--r-lg);overflow:hidden;min-height:430px;
  background:linear-gradient(150deg,var(--green-600),var(--green-400));box-shadow:var(--shadow-lg);
  display:flex;align-items:center;justify-content:center;}
.about-visual::before{content:'';position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.07) 1px,transparent 1px);background-size:22px 22px;}
.about-est{position:relative;text-align:center;color:#fff;z-index:2;padding:30px;}
.about-est .yr{font-family:'Fraunces',serif;font-size:5rem;font-weight:600;line-height:1;}
.about-est .lb{font-size:.95rem;letter-spacing:2px;text-transform:uppercase;opacity:.85;margin-top:8px;}
.about-est .ln{width:60px;height:3px;background:var(--amber);margin:22px auto;border-radius:3px;}
.about-est p{font-size:.95rem;opacity:.9;max-width:300px;margin:0 auto;line-height:1.7;}
.about-mini-cup{position:absolute;bottom:24px;right:24px;width:54px;height:54px;border-radius:50%;background:rgba(255,255,255,.18);border:2px solid rgba(255,255,255,.4);display:flex;align-items:center;justify-content:center;backdrop-filter:blur(4px);}
.about-mini-cup svg{width:28px;height:28px;color:#fff;}
.about-body h2{font-size:clamp(1.8rem,3vw,2.5rem);margin-bottom:20px;}
.about-body p{color:var(--ink-500);margin-bottom:16px;font-size:1rem;line-height:1.8;}
.about-points{display:flex;flex-direction:column;gap:13px;margin-top:24px;}
.about-point{display:flex;gap:13px;align-items:flex-start;}
.about-point .pi{width:30px;height:30px;border-radius:9px;background:var(--green-50);color:var(--green-600);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.about-point .pi svg{width:17px;height:17px;}
.about-point div b{color:var(--ink-900);font-size:.95rem;}
.about-point div p{margin:0;font-size:.88rem;}

/* stats */
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:22px;margin-top:72px;}
.stat{
  background:var(--white);border:1px solid var(--line);border-radius:var(--r-md);padding:30px 26px;
  text-align:center;box-shadow:var(--shadow-sm);transition:all .3s;position:relative;overflow:hidden;
}
.stat::after{content:'';position:absolute;left:0;right:0;bottom:0;height:3px;background:linear-gradient(90deg,var(--green-400),var(--amber));transform:scaleX(0);transform-origin:left;transition:transform .4s;}
.stat:hover{transform:translateY(-6px);box-shadow:var(--shadow-md);border-color:var(--green-200);}
.stat:hover::after{transform:scaleX(1);}
.stat-ic{width:50px;height:50px;border-radius:14px;background:var(--green-50);color:var(--green-600);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;}
.stat-ic svg{width:25px;height:25px;}
.stat-num{font-family:'Fraunces',serif;font-size:2.6rem;font-weight:600;color:var(--ink-900);line-height:1;}
.stat-num .plus{color:var(--green-500);}
.stat-lb{font-size:.86rem;color:var(--ink-400);margin-top:8px;font-weight:500;}

/* ════════ PRODUK ════════ */
.prod-search{max-width:440px;margin:28px auto 48px;position:relative;}
.prod-search input{
  width:100%;padding:15px 20px 15px 50px;border-radius:var(--r-full);border:1.5px solid var(--line);
  background:var(--white);font-family:inherit;font-size:.95rem;color:var(--ink-700);outline:none;transition:all .25s;box-shadow:var(--shadow-sm);
}
.prod-search input:focus{border-color:var(--green-400);box-shadow:0 0 0 4px rgba(45,160,66,.1);}
.prod-search svg{position:absolute;left:19px;top:50%;transform:translateY(-50%);width:19px;height:19px;color:var(--ink-400);}
.prod-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:24px;}
.prod-card{
  background:var(--white);border:1px solid var(--line);border-radius:var(--r-md);overflow:hidden;
  box-shadow:var(--shadow-sm);transition:all .35s cubic-bezier(.16,1,.3,1);cursor:pointer;
}
.prod-card:hover{transform:translateY(-8px);box-shadow:var(--shadow-lg);border-color:var(--green-200);}
.prod-thumb{height:170px;position:relative;display:flex;align-items:center;justify-content:center;overflow:hidden;}
.prod-thumb.v1{background:linear-gradient(160deg,#f6c453,#e8941f);}
.prod-thumb.v2{background:linear-gradient(160deg,#7fce8e,#2da042);}
.prod-thumb.v3{background:linear-gradient(160deg,#f3a5a5,#e23b3b);}
.prod-thumb.v4{background:linear-gradient(160deg,#c9b08a,#8a5a2b);}
.prod-thumb.v5{background:linear-gradient(160deg,#a0d8c0,#1f9e7a);}
.prod-thumb.v6{background:linear-gradient(160deg,#f6d365,#fda085);}
.prod-thumb.v7{background:linear-gradient(160deg,#b6e0a0,#5fa83c);}
.prod-thumb.v8{background:linear-gradient(160deg,#e8b3d0,#b5478a);}
/* mini glass illustration in thumb */
.mini-glass{width:62px;height:88px;position:relative;filter:drop-shadow(0 6px 10px rgba(0,0,0,.18));transition:transform .35s;}
.prod-card:hover .mini-glass{transform:scale(1.08) translateY(-4px);}
.mini-glass .mg-body{position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:48px;height:74px;background:rgba(255,255,255,.45);border:2px solid rgba(255,255,255,.85);border-radius:5px 5px 12px 12px;overflow:hidden;}
.mini-glass .mg-fill{position:absolute;bottom:0;left:0;right:0;height:70%;background:rgba(255,255,255,.55);}
.mini-glass .mg-straw{position:absolute;top:-12px;left:56%;width:7px;height:64px;background:rgba(255,255,255,.85);border-radius:4px;transform:rotate(12deg);}
.prod-tagpop{position:absolute;top:12px;left:12px;background:rgba(255,255,255,.92);color:var(--ink-900);font-size:.68rem;font-weight:800;padding:4px 10px;border-radius:var(--r-full);letter-spacing:.3px;}
.prod-tagpop.hot{background:var(--red);color:#fff;}
.prod-info{padding:18px 18px 20px;}
.prod-name{font-size:1.02rem;font-weight:800;color:var(--ink-900);margin-bottom:5px;}
.prod-desc{font-size:.82rem;color:var(--ink-400);line-height:1.55;margin-bottom:14px;min-height:38px;}
.prod-foot{display:flex;align-items:center;justify-content:space-between;}
.prod-price{font-weight:800;color:var(--green-600);font-size:1.1rem;}
.prod-price small{font-size:.72rem;color:var(--ink-400);font-weight:500;}
.prod-add{width:36px;height:36px;border-radius:11px;background:var(--green-50);color:var(--green-600);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .25s;}
.prod-add:hover{background:var(--green-500);color:#fff;transform:rotate(90deg);}
.prod-add svg{width:18px;height:18px;}
.no-result{grid-column:1/-1;text-align:center;padding:50px;color:var(--ink-400);}
.no-result svg{width:46px;height:46px;margin-bottom:14px;color:var(--green-200);}

/* ════════ KEUNGGULAN ════════ */
.feat-band{background:linear-gradient(165deg,var(--green-50),var(--paper));}
.feat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:22px;}
.feat-card{
  background:var(--white);border:1px solid var(--line);border-radius:var(--r-md);padding:32px 26px;
  box-shadow:var(--shadow-sm);transition:all .35s;position:relative;overflow:hidden;
}
.feat-card:hover{transform:translateY(-6px);box-shadow:var(--shadow-md);border-color:var(--green-200);}
.feat-ic{
  width:58px;height:58px;border-radius:17px;display:flex;align-items:center;justify-content:center;margin-bottom:20px;
  background:linear-gradient(150deg,var(--green-500),var(--green-400));color:#fff;box-shadow:var(--shadow-green);
}
.feat-ic svg{width:28px;height:28px;}
.feat-card:nth-child(2) .feat-ic{background:linear-gradient(150deg,var(--amber),var(--amber-dark));box-shadow:0 14px 30px rgba(245,179,1,.3);}
.feat-card:nth-child(3) .feat-ic{background:linear-gradient(150deg,var(--red),var(--red-dark));box-shadow:0 14px 30px rgba(226,59,59,.28);}
.feat-card:nth-child(4) .feat-ic{background:linear-gradient(150deg,var(--green-600),var(--green-700));}
.feat-card h3{font-size:1.12rem;margin-bottom:9px;}
.feat-card p{font-size:.9rem;color:var(--ink-500);line-height:1.65;}
.feat-no{position:absolute;top:22px;right:24px;font-family:'Fraunces',serif;font-size:2.2rem;color:var(--green-100);font-weight:600;}

/* ════════ DASHBOARD PREVIEW ════════ */
.dash-band{background:var(--ink-900);color:#fff;overflow:hidden;}
[data-theme="dark"] .dash-band{background:#081109;}
.dash-band .sec-tag{background:rgba(255,255,255,.08);color:var(--green-200);border-color:rgba(255,255,255,.12);}
.dash-band .sec-title{color:#fff;}
.dash-band .sec-lead{color:rgba(255,255,255,.7);}
.dash-mock{
  margin-top:54px;background:#0c1a13;border:1px solid rgba(255,255,255,.1);border-radius:var(--r-lg);
  box-shadow:0 40px 90px rgba(0,0,0,.5);overflow:hidden;
}
.dash-bar{display:flex;align-items:center;gap:8px;padding:14px 18px;background:rgba(255,255,255,.04);border-bottom:1px solid rgba(255,255,255,.08);}
.dash-dot{width:11px;height:11px;border-radius:50%;}
.dash-dot.r{background:#ff5f57}.dash-dot.y{background:#febc2e}.dash-dot.g{background:#28c840}
.dash-url{margin-left:14px;font-size:.78rem;color:rgba(255,255,255,.4);font-family:monospace;}
.dash-body{display:grid;grid-template-columns:200px 1fr;min-height:430px;}
.dash-side{background:rgba(255,255,255,.025);border-right:1px solid rgba(255,255,255,.07);padding:18px 14px;}
.dms-brand{display:flex;align-items:center;gap:9px;margin-bottom:22px;padding:0 6px;}
.dms-brand img{width:30px;height:30px;border-radius:50%;}
.dms-brand b{font-size:.92rem;color:#fff;}
.dms-item{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:10px;font-size:.82rem;color:rgba(255,255,255,.6);margin-bottom:3px;}
.dms-item svg{width:16px;height:16px;}
.dms-item.on{background:rgba(74,186,94,.18);color:#7fce8e;font-weight:600;}
.dash-main{padding:24px;}
.dms-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;}
.dms-stat{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:16px;}
.dms-stat .ic{width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:11px;}
.dms-stat .ic svg{width:18px;height:18px;}
.dms-stat .v{font-family:'Fraunces',serif;font-size:1.5rem;font-weight:600;color:#fff;line-height:1;}
.dms-stat .l{font-size:.72rem;color:rgba(255,255,255,.45);margin-top:5px;}
.dms-lower{display:grid;grid-template-columns:1.6fr 1fr;gap:16px;}
.dms-panel{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:18px;}
.dms-panel h4{font-size:.85rem;color:#fff;margin-bottom:4px;}
.dms-panel .ph{font-size:.7rem;color:rgba(255,255,255,.4);margin-bottom:16px;}
#dashChart{width:100%;height:150px;}
.dms-prod{display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid rgba(255,255,255,.06);}
.dms-prod:last-child{border:none;}
.dms-prod .rk{width:24px;height:24px;border-radius:7px;background:rgba(74,186,94,.18);color:#7fce8e;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;flex-shrink:0;}
.dms-prod .nm{flex:1;font-size:.8rem;color:rgba(255,255,255,.8);}
.dms-prod .qt{font-size:.76rem;color:#7fce8e;font-weight:700;}
.dash-cta{text-align:center;margin-top:44px;}

/* ════════ KEMITRAAN / PRICING ════════ */
.price-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;align-items:stretch;margin-top:18px;}
.price-card{
  background:var(--white);border:1.5px solid var(--line);border-radius:var(--r-lg);padding:34px 30px;
  box-shadow:var(--shadow-sm);transition:all .35s;display:flex;flex-direction:column;position:relative;
}
.price-card:hover{transform:translateY(-8px);box-shadow:var(--shadow-lg);}
.price-card.pop{border-color:var(--green-400);box-shadow:var(--shadow-green);transform:scale(1.04);}
.price-card.pop:hover{transform:scale(1.04) translateY(-8px);}
.pop-badge{position:absolute;top:-14px;left:50%;transform:translateX(-50%);background:var(--amber);color:var(--ink-900);font-size:.72rem;font-weight:800;padding:6px 18px;border-radius:var(--r-full);letter-spacing:.4px;box-shadow:0 6px 16px rgba(245,179,1,.4);}
.price-name{font-size:1.2rem;font-weight:800;margin-bottom:5px;display:flex;align-items:center;gap:9px;}
.price-name .pn-ic{width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;}
.price-name .pn-ic svg{width:19px;height:19px;}
.tier-silver{background:#eef1f4;color:#7c8a99;}
.tier-gold{background:var(--amber-soft);color:var(--amber-dark);}
.tier-plat{background:var(--green-50);color:var(--green-600);}
.price-desc{font-size:.85rem;color:var(--ink-400);margin-bottom:22px;}
.price-amt{margin-bottom:4px;}
.price-amt .cur{font-size:1rem;color:var(--ink-500);font-weight:600;vertical-align:top;}
.price-amt .val{font-family:'Fraunces',serif;font-size:2.9rem;font-weight:600;color:var(--ink-900);}
.price-per{font-size:.82rem;color:var(--ink-400);margin-bottom:24px;}
.price-feats{list-style:none;display:flex;flex-direction:column;gap:13px;margin-bottom:28px;flex:1;}
.price-feats li{display:flex;align-items:flex-start;gap:10px;font-size:.88rem;color:var(--ink-700);}
.price-feats li svg{width:18px;height:18px;flex-shrink:0;margin-top:1px;}
.price-feats li.yes svg{color:var(--green-500);}
.price-feats li.no{color:var(--ink-400);}
.price-feats li.no svg{color:var(--ink-400);opacity:.6;}
.price-btn{
  padding:13px;border-radius:var(--r-full);font-weight:700;font-size:.92rem;text-align:center;cursor:pointer;transition:all .25s;border:1.5px solid var(--green-500);color:var(--green-600);background:var(--white);
}
.price-btn:hover{background:var(--green-50);}
.price-btn.fill{background:var(--green-500);color:#fff;border-color:var(--green-500);box-shadow:var(--shadow-green);}
.price-btn.fill:hover{background:var(--green-600);transform:translateY(-2px);}

/* ════════ TESTIMONI ════════ */
.testi-band{background:linear-gradient(165deg,var(--green-50),var(--paper));}
.testi-stage{max-width:820px;margin:40px auto 0;position:relative;}
.testi-track{display:flex;transition:transform .55s cubic-bezier(.16,1,.3,1);}
.testi-card{min-width:100%;padding:0 6px;}
.testi-inner{background:var(--white);border:1px solid var(--line);border-radius:var(--r-lg);padding:44px 46px;box-shadow:var(--shadow-md);text-align:center;}
.testi-quote{font-size:1.7rem;color:var(--green-200);font-family:'Fraunces',serif;line-height:0;height:20px;}
.testi-text{font-size:1.12rem;color:var(--ink-700);line-height:1.7;margin:18px 0 26px;font-weight:500;}
.testi-stars{display:flex;justify-content:center;gap:3px;margin-bottom:22px;}
.testi-stars svg{width:19px;height:19px;color:var(--amber);}
.testi-who{display:flex;align-items:center;justify-content:center;gap:14px;}
.testi-ava{width:54px;height:54px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:800;color:#fff;font-size:1.1rem;flex-shrink:0;}
.testi-who .nm{text-align:left;}
.testi-who .nm b{font-size:1rem;color:var(--ink-900);display:block;}
.testi-who .nm span{font-size:.82rem;color:var(--ink-400);}
.testi-nav{display:flex;align-items:center;justify-content:center;gap:14px;margin-top:30px;}
.testi-arrow{width:46px;height:46px;border-radius:50%;border:1px solid var(--line);background:var(--white);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .25s;color:var(--ink-700);box-shadow:var(--shadow-sm);}
.testi-arrow:hover{background:var(--green-500);color:#fff;border-color:var(--green-500);transform:translateY(-2px);}
.testi-arrow svg{width:20px;height:20px;}
.testi-dots{display:flex;gap:8px;}
.testi-dot{width:9px;height:9px;border-radius:50%;background:var(--green-200);cursor:pointer;transition:all .25s;}
.testi-dot.on{background:var(--green-500);width:26px;border-radius:5px;}

/* ════════ FAQ ════════ */
.faq-wrap{max-width:760px;margin:42px auto 0;}
.faq-item{background:var(--white);border:1px solid var(--line);border-radius:var(--r-md);margin-bottom:13px;overflow:hidden;transition:all .3s;}
.faq-item.open{border-color:var(--green-300);box-shadow:var(--shadow-sm);}
.faq-q{display:flex;align-items:center;gap:14px;padding:20px 24px;cursor:pointer;font-weight:700;color:var(--ink-900);font-size:1rem;}
.faq-q .qn{width:30px;height:30px;border-radius:9px;background:var(--green-50);color:var(--green-600);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-family:'Fraunces',serif;font-weight:600;font-size:.9rem;}
.faq-tog{margin-left:auto;width:26px;height:26px;flex-shrink:0;position:relative;transition:transform .3s;}
.faq-tog::before,.faq-tog::after{content:'';position:absolute;background:var(--green-500);border-radius:2px;}
.faq-tog::before{top:50%;left:4px;right:4px;height:2.5px;transform:translateY(-50%);}
.faq-tog::after{left:50%;top:4px;bottom:4px;width:2.5px;transform:translateX(-50%);transition:opacity .3s;}
.faq-item.open .faq-tog::after{opacity:0;}
.faq-a{max-height:0;overflow:hidden;transition:max-height .35s ease;}
.faq-a-in{padding:0 24px 22px 68px;color:var(--ink-500);font-size:.92rem;line-height:1.7;}

/* ════════ CONTACT ════════ */
.contact-grid{display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:stretch;margin-top:20px;}
.contact-form{background:var(--white);border:1px solid var(--line);border-radius:var(--r-lg);padding:36px;box-shadow:var(--shadow-sm);}
.cf-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
.cf-group{margin-bottom:18px;}
.cf-label{display:block;font-size:.82rem;font-weight:700;color:var(--ink-700);margin-bottom:7px;}
.cf-input,.cf-textarea{width:100%;padding:12px 15px;border-radius:var(--r-sm);border:1.5px solid var(--line);background:var(--paper);font-family:inherit;font-size:.9rem;color:var(--ink-900);outline:none;transition:all .25s;}
.cf-input:focus,.cf-textarea:focus{border-color:var(--green-400);background:var(--white);box-shadow:0 0 0 3px rgba(45,160,66,.1);}
.cf-textarea{resize:vertical;min-height:110px;}
.cf-submit{width:100%;padding:14px;border-radius:var(--r-full);background:var(--green-500);color:#fff;font-weight:700;font-size:.95rem;border:none;cursor:pointer;box-shadow:var(--shadow-green);transition:all .25s;display:flex;align-items:center;justify-content:center;gap:9px;}
.cf-submit:hover{background:var(--green-600);transform:translateY(-2px);}
.contact-aside{display:flex;flex-direction:column;gap:18px;}
.contact-info-card{background:linear-gradient(160deg,var(--green-600),var(--green-500));border-radius:var(--r-lg);padding:32px;color:#fff;box-shadow:var(--shadow-green);}
.contact-info-card h3{color:#fff;font-size:1.3rem;margin-bottom:8px;}
.contact-info-card>p{color:rgba(255,255,255,.82);font-size:.9rem;margin-bottom:24px;}
.ci-line{display:flex;align-items:center;gap:14px;margin-bottom:16px;}
.ci-ic{width:42px;height:42px;border-radius:12px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.ci-ic svg{width:20px;height:20px;color:#fff;}
.ci-line div b{font-size:.9rem;display:block;}
.ci-line div span{font-size:.82rem;color:rgba(255,255,255,.75);}
.ci-socials{display:flex;gap:10px;margin-top:24px;}
.ci-soc{width:42px;height:42px;border-radius:12px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;transition:all .25s;}
.ci-soc:hover{background:#fff;color:var(--green-600);transform:translateY(-3px);}
.ci-soc svg{width:19px;height:19px;}
.contact-map{flex:1;border-radius:var(--r-lg);overflow:hidden;min-height:200px;border:1px solid var(--line);box-shadow:var(--shadow-sm);}
.contact-map iframe{width:100%;height:100%;border:0;min-height:200px;filter:grayscale(.2);}

/* ════════ FOOTER ════════ */
.foot{background:var(--ink-900);color:rgba(255,255,255,.7);padding:70px 0 0;}
[data-theme="dark"] .foot{background:#081109;}
.foot-grid{display:grid;grid-template-columns:1.6fr 1fr 1fr 1fr;gap:40px;padding-bottom:50px;}
.foot-brand .brand-name{color:#fff;}
.foot-brand p{font-size:.88rem;line-height:1.7;margin:18px 0;max-width:300px;color:rgba(255,255,255,.6);}
.foot-col h4{color:#fff;font-size:.95rem;margin-bottom:18px;}
.foot-col a{display:block;font-size:.86rem;color:rgba(255,255,255,.6);margin-bottom:11px;transition:all .2s;}
.foot-col a:hover{color:var(--green-200);transform:translateX(4px);}
.foot-bottom{border-top:1px solid rgba(255,255,255,.1);padding:22px 0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;font-size:.82rem;color:rgba(255,255,255,.5);}
.foot-socials{display:flex;gap:10px;}
.foot-soc{width:38px;height:38px;border-radius:10px;background:rgba(255,255,255,.08);display:flex;align-items:center;justify-content:center;transition:all .25s;}
.foot-soc:hover{background:var(--green-500);color:#fff;transform:translateY(-3px);}
.foot-soc svg{width:17px;height:17px;}

/* ════════ FLOATING ════════ */
.fab-wa{position:fixed;bottom:26px;right:26px;z-index:900;width:58px;height:58px;border-radius:50%;background:#25d366;display:flex;align-items:center;justify-content:center;box-shadow:0 10px 30px rgba(37,211,102,.5);cursor:pointer;transition:all .3s;animation:pulse 2.4s infinite;}
.fab-wa:hover{transform:scale(1.1) translateY(-3px);}
.fab-wa svg{width:30px;height:30px;color:#fff;}
@keyframes pulse{0%{box-shadow:0 10px 30px rgba(37,211,102,.5),0 0 0 0 rgba(37,211,102,.5)}70%{box-shadow:0 10px 30px rgba(37,211,102,.5),0 0 0 16px rgba(37,211,102,0)}100%{box-shadow:0 10px 30px rgba(37,211,102,.5),0 0 0 0 rgba(37,211,102,0)}}
.fab-top{position:fixed;bottom:26px;right:96px;z-index:900;width:52px;height:52px;border-radius:50%;background:var(--white);border:1px solid var(--line);display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:var(--shadow-md);opacity:0;visibility:hidden;transform:translateY(16px);transition:all .35s;color:var(--green-600);}
.fab-top.show{opacity:1;visibility:visible;transform:none;}
.fab-top:hover{background:var(--green-500);color:#fff;transform:translateY(-3px);}
.fab-top svg{width:22px;height:22px;}

/* ════════ RESPONSIVE ════════ */
@media (max-width:980px){
  .hero-grid{grid-template-columns:1fr;gap:30px;text-align:center;}
  .hero-visual{min-height:380px;order:-1;}
  .hero-sub{margin-left:auto;margin-right:auto;}
  .hero-btns,.hero-trust{justify-content:center;}
  .about-grid,.contact-grid{grid-template-columns:1fr;gap:36px;}
  .about-visual{min-height:320px;order:-1;}
  .stats{grid-template-columns:repeat(2,1fr);}
  .prod-grid,.feat-grid{grid-template-columns:repeat(2,1fr);}
  .price-grid{grid-template-columns:1fr;max-width:440px;margin-inline:auto;}
  .price-card.pop{transform:none;}
  .price-card.pop:hover{transform:translateY(-8px);}
  .dms-stats{grid-template-columns:repeat(2,1fr);}
  .dms-lower{grid-template-columns:1fr;}
  .dash-body{grid-template-columns:1fr;}
  .dash-side{display:none;}
  .foot-grid{grid-template-columns:1fr 1fr;gap:32px;}
}
@media (max-width:680px){
  .wrap{padding:0 20px;}
  .nav-links{display:none;}
  .hamburger{display:flex;}
  .nav-cta{display:none;}
  .sec{padding:68px 0;}
  .prod-grid,.feat-grid{grid-template-columns:1fr;max-width:360px;margin-inline:auto;}
  .cf-row{grid-template-columns:1fr;}
  .foot-grid{grid-template-columns:1fr;}
  .testi-inner{padding:32px 24px;}
  .hero-trust{gap:20px;}
  .fab-top{right:92px;}
}
</style>
</head>
<body>
<!-- PRELOADER -->
<div id="preloader">
  <div class="pl-cup"><div class="pl-glass"><div class="pl-tea"></div></div></div>
  <div class="pl-text">Teh <b>Kitta</b></div>
</div>
<!-- ════════ NAVBAR ════════ -->
<nav class="nav" id="nav">
  <div class="nav-inner">
    <a href="#beranda" class="brand">
      <img src="logo.png" alt="Teh Kitta">
      <div>
        <div class="brand-name">Teh <span>Kitta</span></div>
        <div class="brand-tag">Teh Asli Indonesia</div>
      </div>
    </a>
    <div class="nav-links">
      <a href="#beranda" class="nav-link">Beranda</a>
      <a href="#tentang" class="nav-link">Tentang Kami</a>
      <a href="#produk" class="nav-link">Produk</a>
      <a href="#kemitraan" class="nav-link">Kemitraan</a>
      <a href="<?= $dash_link ?>" class="nav-link">Dashboard</a>
      <a href="#kontak" class="nav-link">Kontak</a>
    </div>
    <div class="nav-tools">
      <button class="icon-toggle" id="themeBtn" title="Mode gelap / terang" aria-label="Ganti tema">
        <svg class="ic-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
      </button>
      <a href="#kemitraan" class="nav-cta">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Gabung Kemitraan
      </a>
      <button class="icon-toggle hamburger" id="hamBtn" aria-label="Menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
    </div>
  </div>
</nav>

<!-- MOBILE DRAWER -->
<div class="drawer" id="drawer">
  <div class="drawer-bg" data-close></div>
  <div class="drawer-panel">
    <div class="drawer-head">
      <a href="#beranda" class="brand" data-close>
        <img src="logo.png" alt="Teh Kitta" style="width:38px;height:38px;">
        <div class="brand-name" style="color:var(--ink-900)">Teh <span>Kitta</span></div>
      </a>
      <button class="icon-toggle" data-close aria-label="Tutup">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <a href="#beranda" class="drawer-link" data-close>Beranda</a>
    <a href="#tentang" class="drawer-link" data-close>Tentang Kami</a>
    <a href="#produk" class="drawer-link" data-close>Produk</a>
    <a href="#kemitraan" class="drawer-link" data-close>Kemitraan</a>
    <a href="<?= $dash_link ?>" class="drawer-link" data-close>Dashboard</a>
    <a href="#kontak" class="drawer-link" data-close>Kontak</a>
    <a href="#kemitraan" class="nav-cta" data-close style="margin-top:14px;justify-content:center;">Gabung Kemitraan</a>
  </div>
</div>

<!-- ════════ HERO ════════ -->
<header class="hero" id="beranda">
  <div class="hero-leaf l1"></div>
  <div class="hero-leaf l2"></div>
  <div class="hero-grid">
    <div class="hero-copy">
      <span class="hero-eyebrow reveal in"><span class="dot"></span> Peluang Usaha Minuman Teh Segar</span>
      <h1 class="reveal in d1">Seduhan Asli,<br><span class="accent">Untung Berlipat</span></h1>
      <p class="hero-sub reveal in d2">Teh Kitta menghadirkan teh tubruk asli Indonesia dalam kemasan gelas segar. Bergabunglah jadi mitra dengan modal terjangkau, resep teruji, dan sistem usaha yang sudah berjalan rapi.</p>
      <div class="hero-btns reveal in d3">
        <a href="#kemitraan" class="btn-amber">
          Mulai Jadi Mitra
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
        <a href="#produk" class="btn-glass">Lihat Menu Kami</a>
      </div>
      <div class="hero-trust reveal in d4">
        <div class="t"><b>320+</b><span>Outlet Mitra</span></div>
        <div class="t"><b>48</b><span>Kota & Kabupaten</span></div>
        <div class="t"><b>4.9★</b><span>Rating Mitra</span></div>
      </div>
    </div>
    <div class="hero-visual">
      <div class="float-chip fc-1">
        <div class="fc-ic" style="background:var(--green-50);color:var(--green-600);">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg>
        </div>
        <div><b>+38%</b><span>Omzet mitra/tahun</span></div>
      </div>
      <div class="cup-card">
        <div class="cup">
          <span class="bubble" style="width:7px;height:7px;left:30%;bottom:60px;animation-delay:.2s"></span>
          <span class="bubble" style="width:5px;height:5px;left:55%;bottom:50px;animation-delay:1.2s"></span>
          <span class="bubble" style="width:6px;height:6px;left:44%;bottom:70px;animation-delay:2.1s"></span>
          <div class="cup-lid"></div>
          <div class="straw"></div>
          <div class="cup-body">
            <div class="cup-tea"></div>
            <span class="ice i1"></span><span class="ice i2"></span><span class="ice i3"></span>
          </div>
          <div class="cup-badge"><img src="logo.png" alt="Teh Kitta"></div>
        </div>
      </div>
      <div class="float-chip fc-2">
        <div class="fc-ic" style="background:var(--amber-soft);color:var(--amber-dark);">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <div><b>1.2 Juta+</b><span>Gelas terjual</span></div>
      </div>
    </div>
  </div>
</header>
<div class="wave" style="margin-top:-1px;"><svg viewBox="0 0 1440 70" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><path d="M0,32 C240,72 480,72 720,44 C960,16 1200,16 1440,40 L1440,70 L0,70 Z" fill="var(--paper)"/></svg></div>

<!-- ════════ TENTANG KAMI ════════ -->
<section class="sec" id="tentang">
  <div class="wrap">
    <div class="about-grid">
      <div class="about-visual reveal">
        <div class="about-est">
          <div class="yr">2009</div>
          <div class="lb">Sejak</div>
          <div class="ln"></div>
          <p>Berawal dari satu gerobak teh di pinggir pasar, kini tumbuh jadi keluarga besar mitra di seluruh negeri.</p>
        </div>
        <div class="about-mini-cup">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
        </div>
      </div>
      <div class="about-body reveal d1">
        <span class="sec-tag">Tentang Kami</span>
        <h2>Teh sederhana yang <em>dirindukan</em> banyak orang</h2>
        <p>Teh Kitta lahir dari keyakinan sederhana: teh yang enak tak perlu mahal. Kami menyeduh daun teh pilihan dengan takaran yang konsisten, lalu menyajikannya segar dalam gelas yang praktis dibawa ke mana saja.</p>
        <p>Hari ini, ratusan mitra mengandalkan resep, pasokan bahan, dan sistem pencatatan kami untuk menjalankan usaha mereka sendiri, dari kios kecil sampai gerai di pusat keramaian.</p>
        <div class="about-points">
          <div class="about-point">
            <span class="pi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>
            <div><b>Resep & takaran terstandar</b><p>Rasa yang sama di setiap gelas, di mana pun outletnya.</p></div>
          </div>
          <div class="about-point">
            <span class="pi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>
            <div><b>Pasokan bahan terjamin</b><p>Daun teh, gula, dan kemasan dikirim rutin langsung ke mitra.</p></div>
          </div>
          <div class="about-point">
            <span class="pi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>
            <div><b>Pendampingan usaha</b><p>Tim kami bantu dari buka outlet sampai kelola penjualan harian.</p></div>
          </div>
        </div>
      </div>
    </div>

    <!-- STATS -->
    <div class="stats">
      <div class="stat reveal">
        <div class="stat-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
        <div class="stat-num" data-count="320"><span class="num">0</span><span class="plus">+</span></div>
        <div class="stat-lb">Outlet & Cabang Mitra</div>
      </div>
      <div class="stat reveal d1">
        <div class="stat-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
        <div class="stat-num" data-count="285"><span class="num">0</span><span class="plus">+</span></div>
        <div class="stat-lb">Mitra Aktif Berjualan</div>
      </div>
      <div class="stat reveal d2">
        <div class="stat-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg></div>
        <div class="stat-num" data-count="1.2"><span class="num">0</span><span class="plus"> Jt+</span></div>
        <div class="stat-lb">Gelas Teh Terjual</div>
      </div>
      <div class="stat reveal d3">
        <div class="stat-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
        <div class="stat-num" data-count="48"><span class="num">0</span></div>
        <div class="stat-lb">Kota & Kabupaten</div>
      </div>
    </div>
  </div>
</section>

<!-- ════════ PRODUK UNGGULAN ════════ -->
<section class="sec feat-band" id="produk">
  <div class="wave" style="position:absolute;top:0;left:0;transform:rotate(180deg);"><svg viewBox="0 0 1440 70" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><path d="M0,32 C240,72 480,72 720,44 C960,16 1200,16 1440,40 L1440,70 L0,70 Z" fill="var(--paper)"/></svg></div>
  <div class="wrap" style="position:relative;z-index:2;">
    <div class="center">
      <span class="sec-tag reveal">Menu Favorit</span>
      <h2 class="sec-title reveal d1">Pilihan rasa yang <em>laris manis</em></h2>
      <p class="sec-lead reveal d2">Dari teh tubruk klasik sampai racikan susu dan buah segar — semua disajikan dingin dan menyegarkan.</p>
    </div>

    <div class="prod-search reveal d2">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" id="prodSearch" placeholder="Cari menu favoritmu...">
    </div>

    <div class="prod-grid" id="prodGrid">
      <!-- cards injected by JS for searchability + clean markup -->
    </div>
  </div>
</section>

<!-- ════════ KEUNGGULAN BRAND ════════ -->
<section class="sec" id="keunggulan">
  <div class="wrap">
    <div class="center">
      <span class="sec-tag reveal">Kenapa Teh Kitta</span>
      <h2 class="sec-title reveal d1">Usaha teh yang <em>siap jalan</em></h2>
      <p class="sec-lead reveal d2">Kami siapkan hampir semuanya. Mitra tinggal fokus melayani pembeli dan menumbuhkan penjualan.</p>
    </div>
    <div class="feat-grid" style="margin-top:54px;">
      <div class="feat-card reveal">
        <span class="feat-no">01</span>
        <div class="feat-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>
        <h3>Mudah Bergabung</h3>
        <p>Proses pendaftaran ringkas. Daftar, pilih paket, ikuti pelatihan singkat, lalu mulai berjualan dalam hitungan hari.</p>
      </div>
      <div class="feat-card reveal d1">
        <span class="feat-no">02</span>
        <div class="feat-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
        <h3>Modal Terjangkau</h3>
        <p>Tersedia paket mulai dari modal kecil. Peralatan, bahan awal, dan branding sudah termasuk dalam satu paket.</p>
      </div>
      <div class="feat-card reveal d2">
        <span class="feat-no">03</span>
        <div class="feat-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/><path d="M7 8h4M7 12h7"/></svg></div>
        <h3>Sistem Terintegrasi</h3>
        <p>Catat penjualan, kelola stok, dan pantau pemasukan lewat satu dashboard. Laporan harian otomatis terangkum rapi.</p>
      </div>
      <div class="feat-card reveal d3">
        <span class="feat-no">04</span>
        <div class="feat-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div>
        <h3>Dukungan Operasional</h3>
        <p>Tim pendamping siap membantu mulai survei lokasi, pelatihan barista, sampai solusi saat ada kendala di lapangan.</p>
      </div>
    </div>
  </div>
</section>

<!-- ════════ DASHBOARD PREVIEW ════════ -->
<section class="sec dash-band" id="dashboard-preview">
  <div class="wrap">
    <div class="center">
      <span class="sec-tag reveal">Sistem Manajemen</span>
      <h2 class="sec-title reveal d1">Kelola usaha dari <em>satu layar</em></h2>
      <p class="sec-lead reveal d2">Setiap mitra mendapat akses dashboard untuk memantau penjualan, stok, dan transaksi outlet secara langsung.</p>
    </div>


    <div class="dash-cta reveal">
      <a href="<?= $dash_link ?>" class="btn-amber">
        Coba Masuk Dashboard
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
      </a>
    </div>
  </div>
</section>

<!-- ════════ PAKET KEMITRAAN ════════ -->
<section class="sec" id="kemitraan">
  <div class="wrap">
    <div class="center">
      <span class="sec-tag reveal">Paket Kemitraan</span>
      <h2 class="sec-title reveal d1">Pilih paket yang <em>pas</em> untukmu</h2>
      <p class="sec-lead reveal d2">Semua paket sudah termasuk peralatan, bahan awal, branding, dan akses sistem. Tanpa biaya royalti bulanan.</p>
    </div>
    <div class="price-grid">
      <!-- SILVER -->
      <div class="price-card reveal">
        <div class="price-name"><span class="pn-ic tier-silver"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg></span> Silver</div>
        <p class="price-desc">Cocok untuk pemula yang ingin mulai dari kios kecil.</p>
        <div class="price-amt"><span class="cur">Rp</span> <span class="val">4,9</span></div>
        <div class="price-per">juta · sekali bayar</div>
        <ul class="price-feats">
          <li class="yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Booth & peralatan dasar</li>
          <li class="yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Bahan awal 200 gelas</li>
          <li class="yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> 8 varian menu</li>
          <li class="yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Akses dashboard penjualan</li>
          <li class="no"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Survei lokasi gratis</li>
          <li class="no"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Prioritas pasokan bahan</li>
        </ul>
        <a href="#kontak" class="price-btn">Pilih Silver</a>
      </div>
      <!-- GOLD -->
      <div class="price-card pop reveal d1">
        <span class="pop-badge">Paling Diminati</span>
        <div class="price-name"><span class="pn-ic tier-gold"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></span> Gold</div>
        <p class="price-desc">Paket terlengkap untuk outlet di lokasi ramai.</p>
        <div class="price-amt"><span class="cur">Rp</span> <span class="val">9,5</span></div>
        <div class="price-per">juta · sekali bayar</div>
        <ul class="price-feats">
          <li class="yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Booth premium + neon box</li>
          <li class="yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Bahan awal 500 gelas</li>
          <li class="yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> 16 varian menu</li>
          <li class="yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Dashboard + laporan lengkap</li>
          <li class="yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Survei lokasi gratis</li>
          <li class="no"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Prioritas pasokan bahan</li>
        </ul>
        <a href="#kontak" class="price-btn fill">Pilih Gold</a>
      </div>
      <!-- PLATINUM -->
      <div class="price-card reveal d2">
        <div class="price-name"><span class="pn-ic tier-plat"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7.4-6.3-4.6L5.7 21.4 8 14 2 9.4h7.6z"/><circle cx="12" cy="12" r="2"/></svg></span> Platinum</div>
        <p class="price-desc">Untuk mitra serius yang ingin membuka beberapa outlet.</p>
        <div class="price-amt"><span class="cur">Rp</span> <span class="val">17,9</span></div>
        <div class="price-per">juta · sekali bayar</div>
        <ul class="price-feats">
          <li class="yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Gerai eksklusif + interior</li>
          <li class="yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Bahan awal 1.000 gelas</li>
          <li class="yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Seluruh varian menu</li>
          <li class="yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Dashboard multi-outlet</li>
          <li class="yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Survei lokasi gratis</li>
          <li class="yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Prioritas pasokan bahan</li>
        </ul>
        <a href="#kontak" class="price-btn">Pilih Platinum</a>
      </div>
    </div>
  </div>
</section>

<!-- ════════ TESTIMONI ════════ -->
<section class="sec testi-band" id="testimoni">
  <div class="wave" style="position:absolute;top:0;left:0;transform:rotate(180deg);"><svg viewBox="0 0 1440 70" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><path d="M0,40 C240,8 480,8 720,36 C960,64 1200,64 1440,32 L1440,70 L0,70 Z" fill="var(--paper)"/></svg></div>
  <div class="wrap" style="position:relative;z-index:2;">
    <div class="center">
      <span class="sec-tag reveal">Kata Mitra</span>
      <h2 class="sec-title reveal d1">Cerita dari <em>mereka yang sudah jalan</em></h2>
    </div>
    <div class="testi-stage reveal d2">
      <div style="overflow:hidden;">
        <div class="testi-track" id="testiTrack">
          <div class="testi-card">
            <div class="testi-inner">
              <div class="testi-quote">"</div>
              <p class="testi-text">Dulu saya cuma karyawan toko. Sekarang punya dua booth Teh Kitta. Bahannya rutin dikirim, jadi saya tinggal jualan. Balik modal cuma sekitar empat bulan.</p>
              <div class="testi-stars"><svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
              <div class="testi-who"><div class="testi-ava" style="background:linear-gradient(135deg,#4aba5e,#228234)">RW</div><div class="nm"><b>Rudi Wijaya</b><span>Mitra Gold · Bandung</span></div></div>
            </div>
          </div>
          <div class="testi-card">
            <div class="testi-inner">
              <div class="testi-quote">"</div>
              <p class="testi-text">Yang saya suka, semua penjualan kecatat otomatis di dashboard. Saya bisa tahu menu mana yang paling laku dan kapan harus restock. Nggak perlu lagi catat manual di buku.</p>
              <div class="testi-stars"><svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
              <div class="testi-who"><div class="testi-ava" style="background:linear-gradient(135deg,#f5b301,#e0a000)">SN</div><div class="nm"><b>Siti Nurhaliza</b><span>Mitra Silver · Yogyakarta</span></div></div>
            </div>
          </div>
          <div class="testi-card">
            <div class="testi-inner">
              <div class="testi-quote">"</div>
              <p class="testi-text">Tim pusatnya responsif banget. Pas mesin saya bermasalah, langsung dibantu hari itu juga. Rasa tehnya juga konsisten, pelanggan langganan saya nggak pernah komplain.</p>
              <div class="testi-stars"><svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
              <div class="testi-who"><div class="testi-ava" style="background:linear-gradient(135deg,#e23b3b,#c92b2b)">AP</div><div class="nm"><b>Agus Pratama</b><span>Mitra Platinum · Surabaya</span></div></div>
            </div>
          </div>
        </div>
      </div>
      <div class="testi-nav">
        <button class="testi-arrow" id="testiPrev" aria-label="Sebelumnya"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
        <div class="testi-dots" id="testiDots"></div>
        <button class="testi-arrow" id="testiNext" aria-label="Berikutnya"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></button>
      </div>
    </div>
  </div>
</section>

<!-- ════════ FAQ ════════ -->
<section class="sec" id="faq">
  <div class="wrap">
    <div class="center">
      <span class="sec-tag reveal">Pertanyaan Umum</span>
      <h2 class="sec-title reveal d1">Masih ada yang <em>ingin ditanyakan?</em></h2>
    </div>
    <div class="faq-wrap">
      <div class="faq-item reveal">
        <div class="faq-q"><span class="qn">1</span> Berapa modal minimal untuk jadi mitra? <span class="faq-tog"></span></div>
        <div class="faq-a"><div class="faq-a-in">Paket Silver kami mulai dari Rp 4,9 juta dan sudah termasuk booth, peralatan dasar, bahan untuk 200 gelas pertama, serta branding. Tidak ada biaya royalti bulanan, jadi seluruh keuntungan penjualan menjadi milik mitra.</div></div>
      </div>
      <div class="faq-item reveal d1">
        <div class="faq-q"><span class="qn">2</span> Apakah saya perlu pengalaman usaha sebelumnya? <span class="faq-tog"></span></div>
        <div class="faq-a"><div class="faq-a-in">Tidak perlu. Setiap mitra baru mengikuti pelatihan singkat tentang cara menyeduh, melayani pembeli, dan menggunakan dashboard. Banyak mitra kami yang sebelumnya belum pernah berjualan sama sekali.</div></div>
      </div>
      <div class="faq-item reveal d2">
        <div class="faq-q"><span class="qn">3</span> Bagaimana sistem pasokan bahan bakunya? <span class="faq-tog"></span></div>
        <div class="faq-a"><div class="faq-a-in">Daun teh, gula, dan kemasan dikirim secara rutin sesuai kebutuhan outlet. Mitra cukup memesan ulang lewat dashboard ketika stok mulai menipis, dan pesanan akan diproses oleh gudang terdekat.</div></div>
      </div>
      <div class="faq-item reveal d3">
        <div class="faq-q"><span class="qn">4</span> Berapa lama biasanya balik modal? <span class="faq-tog"></span></div>
        <div class="faq-a"><div class="faq-a-in">Bergantung pada lokasi dan keramaian, namun rata-rata mitra kami balik modal dalam 4–8 bulan. Lokasi yang ramai dengan pengelolaan yang baik bisa lebih cepat dari itu.</div></div>
      </div>
      <div class="faq-item reveal d4">
        <div class="faq-q"><span class="qn">5</span> Apakah wilayah outlet saya dilindungi? <span class="faq-tog"></span></div>
        <div class="faq-a"><div class="faq-a-in">Ya. Kami menerapkan batas jarak antar outlet mitra agar tidak saling berebut pembeli di area yang sama. Tim kami akan membantu memetakan lokasi sebelum outlet dibuka.</div></div>
      </div>
    </div>
  </div>
</section>

<!-- ════════ KONTAK ════════ -->
<section class="sec feat-band" id="kontak">
  <div class="wave" style="position:absolute;top:0;left:0;transform:rotate(180deg);"><svg viewBox="0 0 1440 70" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><path d="M0,32 C240,72 480,72 720,44 C960,16 1200,16 1440,40 L1440,70 L0,70 Z" fill="var(--paper)"/></svg></div>
  <div class="wrap" style="position:relative;z-index:2;">
    <div class="center">
      <span class="sec-tag reveal">Hubungi Kami</span>
      <h2 class="sec-title reveal d1">Siap memulai? <em>Mari bicara</em></h2>
      <p class="sec-lead reveal d2">Tinggalkan pesan, tim kemitraan kami akan menghubungi Anda untuk diskusi lokasi dan paket yang cocok.</p>
    </div>
    <div class="contact-grid">
      <div class="contact-form reveal">
        <form id="contactForm" novalidate>
          <div class="cf-row">
            <div class="cf-group"><label class="cf-label">Nama Lengkap</label><input class="cf-input" type="text" name="nama" placeholder="Nama Anda" required></div>
            <div class="cf-group"><label class="cf-label">Nomor WhatsApp</label><input class="cf-input" type="tel" name="telp" placeholder="08xxxxxxxxxx" required></div>
          </div>
          <div class="cf-group"><label class="cf-label">Email</label><input class="cf-input" type="email" name="email" placeholder="nama@email.com"></div>
          <div class="cf-group"><label class="cf-label">Kota / Domisili</label><input class="cf-input" type="text" name="kota" placeholder="Kota tempat Anda ingin membuka outlet"></div>
          <div class="cf-group"><label class="cf-label">Pesan</label><textarea class="cf-textarea" name="pesan" placeholder="Ceritakan rencana usaha Anda..."></textarea></div>
          <button type="submit" class="cf-submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            Kirim Pesan
          </button>
        </form>
      </div>
      <div class="contact-aside">
        <div class="contact-info-card reveal d1">
          <h3>Kantor Pusat Teh Kitta</h3>
          <p>Senin – Sabtu, 08.00 – 17.00 WIB</p>
          <div class="ci-line"><span class="ci-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span><div><b>Jl. Melati Raya No. 12, Jakarta</b><span>Indonesia</span></div></div>
          <div class="ci-line"><span class="ci-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span><div><b>0800-1234-5678</b><span>Bebas pulsa</span></div></div>
          <div class="ci-line"><span class="ci-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 7L2 7"/></svg></span><div><b>halo@tehkitta.id</b><span>Email kemitraan</span></div></div>
          <div class="ci-socials">
            <a class="ci-soc" href="#" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><line x1="17.5" y1="6.5" x2="17.5" y2="6.5"/></svg></a>
            <a class="ci-soc" href="#" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a>
            <a class="ci-soc" href="#" aria-label="TikTok"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 8.2a5.6 5.6 0 0 0 3.5 1.2V6.5a2.9 2.9 0 0 1-1.9-.9 2.9 2.9 0 0 1-1-2.1H13v11.4a2.4 2.4 0 1 1-2.4-2.4c.2 0 .4 0 .6.1V9.5a5.5 5.5 0 1 0 4.8 5.4z"/></svg></a>
          </div>
        </div>
        <div class="contact-map reveal d2">
          <iframe src="https://www.openstreetmap.org/export/embed.html?bbox=106.8090%2C-6.2300%2C106.8490%2C-6.2000&layer=mapnik&marker=-6.2150%2C106.8290" loading="lazy" title="Lokasi Teh Kitta"></iframe>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ════════ FOOTER ════════ -->
<footer class="foot">
  <div class="wrap">
    <div class="foot-grid">
      <div class="foot-brand">
        <a href="#beranda" class="brand">
          <img src="logo.png" alt="Teh Kitta" style="width:46px;height:46px;border-radius:50%;">
          <div><div class="brand-name">Teh <span>Kitta</span></div><div class="brand-tag" style="color:rgba(255,255,255,.5)">Teh Asli Indonesia</div></div>
        </a>
        <p>Jaringan kemitraan minuman teh segar asli Indonesia. Kami bantu Anda memulai usaha dengan modal terjangkau dan sistem yang sudah teruji.</p>
      </div>
      <div class="foot-col">
        <h4>Perusahaan</h4>
        <a href="#tentang">Tentang Kami</a>
        <a href="#produk">Produk</a>
        <a href="#keunggulan">Keunggulan</a>
        <a href="#testimoni">Testimoni</a>
      </div>
      <div class="foot-col">
        <h4>Kemitraan</h4>
        <a href="#kemitraan">Paket Mitra</a>
        <a href="#faq">FAQ</a>
        <a href="#kontak">Daftar Sekarang</a>
        <a href="<?= $dash_link ?>">Login Mitra</a>
      </div>
      <div class="foot-col">
        <h4>Kontak</h4>
        <a href="tel:080012345678">0800-1234-5678</a>
        <a href="mailto:halo@tehkitta.id">halo@tehkitta.id</a>
        <a href="#kontak">Jakarta, Indonesia</a>
      </div>
    </div>
    <div class="foot-bottom">
      <span>© <?= date('Y') ?> Teh Kitta. Seluruh hak cipta dilindungi.</span>
      <div class="foot-socials">
        <a class="foot-soc" href="#" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><line x1="17.5" y1="6.5" x2="17.5" y2="6.5"/></svg></a>
        <a class="foot-soc" href="#" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a>
        <a class="foot-soc" href="#" aria-label="WhatsApp"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.5 15.3L2 22l4.8-1.5A10 10 0 1 0 12 2zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-2.8.9.9-2.7-.2-.3A8 8 0 1 1 12 20zm4.4-6c-.2-.1-1.4-.7-1.6-.8-.2-.1-.4-.1-.5.1l-.7.9c-.1.2-.3.2-.5.1a6.5 6.5 0 0 1-3.2-2.8c-.2-.4.2-.4.6-1.2.1-.1 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.5-.4h-.5c-.2 0-.4.1-.6.3a3 3 0 0 0-.9 2.2c0 1.3.9 2.5 1.1 2.7.1.2 1.9 2.9 4.6 4 1.7.7 2.3.8 3.1.7.5-.1 1.4-.6 1.6-1.1.2-.6.2-1 .1-1.1z"/></svg></a>
      </div>
    </div>
  </div>
</footer>

<!-- FLOATING -->
<button class="fab-top" id="fabTop" aria-label="Kembali ke atas"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg></button>
<a class="fab-wa" href="https://wa.me/6280012345678?text=Halo%20Teh%20Kitta,%20saya%20tertarik%20info%20kemitraan" target="_blank" rel="noopener" aria-label="Chat WhatsApp"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.5 15.3L2 22l4.8-1.5A10 10 0 1 0 12 2zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-2.8.9.9-2.7-.2-.3A8 8 0 1 1 12 20zm4.4-6c-.2-.1-1.4-.7-1.6-.8-.2-.1-.4-.1-.5.1l-.7.9c-.1.2-.3.2-.5.1a6.5 6.5 0 0 1-3.2-2.8c-.2-.4.2-.4.6-1.2.1-.1 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.5-.4h-.5c-.2 0-.4.1-.6.3a3 3 0 0 0-.9 2.2c0 1.3.9 2.5 1.1 2.7.1.2 1.9 2.9 4.6 4 1.7.7 2.3.8 3.1.7.5-.1 1.4-.6 1.6-1.1.2-.6.2-1 .1-1.1z"/></svg></a>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
/* ─── PRELOADER ─── */
window.addEventListener('load',()=>{setTimeout(()=>document.getElementById('preloader').classList.add('done'),650);});

/* ─── THEME TOGGLE ─── */
const themeBtn=document.getElementById('themeBtn');
const savedT=localStorage.getItem('tk-theme');
if(savedT==='dark')document.documentElement.setAttribute('data-theme','dark');
function syncThemeIcon(){
  const dark=document.documentElement.getAttribute('data-theme')==='dark';
  themeBtn.innerHTML=dark
    ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>'
    : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>';
}
syncThemeIcon();
themeBtn.addEventListener('click',()=>{
  const dark=document.documentElement.getAttribute('data-theme')==='dark';
  if(dark){document.documentElement.removeAttribute('data-theme');localStorage.setItem('tk-theme','light');}
  else{document.documentElement.setAttribute('data-theme','dark');localStorage.setItem('tk-theme','dark');}
  syncThemeIcon();
});

/* ─── NAVBAR SCROLL ─── */
const nav=document.getElementById('nav');
const onScroll=()=>{
  nav.classList.toggle('scrolled',window.scrollY>40);
  document.getElementById('fabTop').classList.toggle('show',window.scrollY>500);
};
window.addEventListener('scroll',onScroll);onScroll();

/* ─── MOBILE DRAWER ─── */
const drawer=document.getElementById('drawer');
document.getElementById('hamBtn').addEventListener('click',()=>drawer.classList.add('open'));
drawer.querySelectorAll('[data-close]').forEach(el=>el.addEventListener('click',()=>drawer.classList.remove('open')));

/* ─── BACK TO TOP ─── */
document.getElementById('fabTop').addEventListener('click',()=>window.scrollTo({top:0,behavior:'smooth'}));

/* ─── PRODUCTS (data-driven for search) ─── */
const products=[
  {n:'Es Teh Original',d:'Teh tubruk asli, manis pas, disajikan dingin.',p:'5.000',v:'v1',tag:'Best Seller',hot:true},
  {n:'Teh Susu Gula Aren',d:'Perpaduan teh dan susu dengan gula aren legit.',p:'8.000',v:'v2',tag:'Favorit'},
  {n:'Teh Leci Segar',d:'Teh dengan potongan leci manis menyegarkan.',p:'9.000',v:'v3',tag:''},
  {n:'Teh Tarik Klasik',d:'Teh tarik kental dengan busa lembut.',p:'8.000',v:'v4',tag:''},
  {n:'Teh Melati Dingin',d:'Aroma melati harum, ringan dan menenangkan.',p:'6.000',v:'v5',tag:''},
  {n:'Teh Mangga Yakult',d:'Teh, mangga, dan yakult yang asam manis.',p:'10.000',v:'v6',tag:'Baru'},
  {n:'Teh Lemon Madu',d:'Teh hangat-dingin dengan lemon dan madu.',p:'9.000',v:'v7',tag:''},
  {n:'Teh Taro Creamy',d:'Teh dengan taro lembut dan topping krim.',p:'11.000',v:'v8',tag:''},
];
const grid=document.getElementById('prodGrid');
function renderProducts(list){
  if(!list.length){grid.innerHTML='<div class="no-result"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg><div>Menu tidak ditemukan. Coba kata kunci lain.</div></div>';return;}
  grid.innerHTML=list.map(p=>`
    <div class="prod-card">
      <div class="prod-thumb ${p.v}">
        ${p.tag?`<span class="prod-tagpop ${p.hot?'hot':''}">${p.tag}</span>`:''}
        <div class="mini-glass"><div class="mg-straw"></div><div class="mg-body"><div class="mg-fill"></div></div></div>
      </div>
      <div class="prod-info">
        <div class="prod-name">${p.n}</div>
        <div class="prod-desc">${p.d}</div>
        <div class="prod-foot">
          <div class="prod-price"><small>Rp</small> ${p.p}</div>
          <button class="prod-add" aria-label="Tambah"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
        </div>
      </div>
    </div>`).join('');
  // re-observe new cards for reveal
  grid.querySelectorAll('.prod-card').forEach((c,i)=>{c.classList.add('reveal');c.style.transitionDelay=(i%4*0.06)+'s';io.observe(c);});
}
document.getElementById('prodSearch').addEventListener('input',e=>{
  const q=e.target.value.toLowerCase().trim();
  renderProducts(products.filter(p=>p.n.toLowerCase().includes(q)||p.d.toLowerCase().includes(q)));
});

/* ─── SCROLL REVEAL ─── */
const io=new IntersectionObserver(entries=>{
  entries.forEach(en=>{if(en.isIntersecting){en.target.classList.add('in');io.unobserve(en.target);}});
},{threshold:0.12,rootMargin:'0px 0px -40px 0px'});
document.querySelectorAll('.reveal').forEach(el=>io.observe(el));

/* ─── COUNTER ANIMATION ─── */
const counters=document.querySelectorAll('.stat-num');
const cio=new IntersectionObserver(entries=>{
  entries.forEach(en=>{
    if(en.isIntersecting){
      const el=en.target,target=parseFloat(el.dataset.count),numEl=el.querySelector('.num');
      const dec=target%1!==0;let cur=0;const step=target/55;
      const tick=()=>{cur+=step;if(cur>=target){numEl.textContent=dec?target.toFixed(1):Math.round(target);}else{numEl.textContent=dec?cur.toFixed(1):Math.round(cur);requestAnimationFrame(tick);}};
      tick();cio.unobserve(el);
    }
  });
},{threshold:0.5});
counters.forEach(c=>cio.observe(c));

/* ─── TESTIMONIAL CAROUSEL ─── */
const track=document.getElementById('testiTrack'),dotsWrap=document.getElementById('testiDots');
const total=track.children.length;let idx=0;
for(let i=0;i<total;i++){const d=document.createElement('span');d.className='testi-dot'+(i===0?' on':'');d.addEventListener('click',()=>go(i));dotsWrap.appendChild(d);}
function go(i){idx=(i+total)%total;track.style.transform=`translateX(-${idx*100}%)`;dotsWrap.querySelectorAll('.testi-dot').forEach((d,n)=>d.classList.toggle('on',n===idx));}
document.getElementById('testiNext').addEventListener('click',()=>go(idx+1));
document.getElementById('testiPrev').addEventListener('click',()=>go(idx-1));
let autoT=setInterval(()=>go(idx+1),5500);
track.parentElement.addEventListener('mouseenter',()=>clearInterval(autoT));
track.parentElement.addEventListener('mouseleave',()=>autoT=setInterval(()=>go(idx+1),5500));

/* ─── FAQ ACCORDION ─── */
document.querySelectorAll('.faq-item').forEach(item=>{
  item.querySelector('.faq-q').addEventListener('click',()=>{
    const open=item.classList.contains('open');
    document.querySelectorAll('.faq-item').forEach(o=>{o.classList.remove('open');o.querySelector('.faq-a').style.maxHeight=null;});
    if(!open){item.classList.add('open');const a=item.querySelector('.faq-a');a.style.maxHeight=a.scrollHeight+'px';}
  });
});

/* ─── CONTACT FORM ─── */
document.getElementById('contactForm').addEventListener('submit',e=>{
  e.preventDefault();
  const btn=e.target.querySelector('.cf-submit');
  const orig=btn.innerHTML;
  btn.innerHTML='✓ Terkirim! Kami segera menghubungi Anda';
  btn.style.background='var(--green-600)';
  e.target.reset();
  setTimeout(()=>{btn.innerHTML=orig;btn.style.background='';},3200);
});

/* render products */
renderProducts(products);

/* smooth scroll offset for sticky nav */
document.querySelectorAll('a[href^="#"]').forEach(a=>{
  a.addEventListener('click',function(e){
    const id=this.getAttribute('href');if(id==='#'||id.length<2)return;
    const tgt=document.querySelector(id);if(!tgt)return;
    e.preventDefault();
    const y=tgt.getBoundingClientRect().top+window.scrollY-58;
    window.scrollTo({top:y,behavior:'smooth'});
  });
});
</script>
</body>
</html>
