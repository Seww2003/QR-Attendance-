<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>QR Attendance | Smart System</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<!-- Animate on Scroll -->
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

<style>
:root{
    --primary:#6C63FF;
    --secondary:#36D1DC;
    --accent:#FF6584;
    --dark:#0b0f2b;
    --glass:rgba(255,255,255,0.08);
    --gradient:linear-gradient(135deg,#6C63FF,#36D1DC);
}

body{
    background:radial-gradient(circle at top,#1b1f52,#0b0f2b);
    color:white;
    font-family:'Poppins',sans-serif;
    overflow-x:hidden;
}

/* FLOATING BLOBS */
.blob{
    position:absolute;
    width:300px;
    height:300px;
    background:var(--gradient);
    filter:blur(120px);
    opacity:.4;
    animation:float 12s infinite alternate;
}
.blob.one{top:10%;left:5%}
.blob.two{bottom:10%;right:5%;animation-delay:3s}

@keyframes float{
    from{transform:translateY(0)}
    to{transform:translateY(-80px)}
}

/* HERO */
.hero{
    min-height:100vh;
    display:flex;
    align-items:center;
    position:relative;
}
.hero h1{
    font-size:3.4rem;
    font-weight:800;
}
.hero span{
    background:var(--gradient);
    -webkit-background-clip:text;
    color:transparent;
}
.hero p{
    opacity:.85;
    max-width:520px;
}

/* BUTTONS */
.btn-main{
    background:var(--gradient);
    border:none;
    padding:15px 32px;
    border-radius:14px;
    color:white;
    font-weight:600;
    transition:.4s;
}
.btn-main:hover{
    transform:translateY(-4px);
    box-shadow:0 15px 40px rgba(108,99,255,.6);
}

.btn-glass{
    background:var(--glass);
    border:1px solid rgba(255,255,255,.2);
    padding:15px 32px;
    border-radius:14px;
    color:white;
    font-weight:600;
    backdrop-filter:blur(10px);
    transition:.4s;
}
.btn-glass:hover{
    background:rgba(255,255,255,.15);
}

/* QR CARD */
.qr-card{
    width:340px;
    height:340px;
    background:var(--glass);
    border-radius:30px;
    backdrop-filter:blur(20px);
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 30px 80px rgba(0,0,0,.6);
    animation:floatQR 6s ease-in-out infinite;
}
.qr-card i{
    font-size:150px;
    background:var(--gradient);
    -webkit-background-clip:text;
    color:transparent;
}

@keyframes floatQR{
    0%,100%{transform:translateY(0)}
    50%{transform:translateY(-25px)}
}

/* FEATURES */
.features{
    padding:120px 0;
}
.feature-card{
    background:var(--glass);
    border-radius:25px;
    padding:35px;
    text-align:center;
    transition:.5s;
    position:relative;
    overflow:hidden;
}
.feature-card::before{
    content:'';
    position:absolute;
    inset:0;
    background:var(--gradient);
    opacity:0;
    transition:.5s;
}
.feature-card:hover::before{
    opacity:.15;
}
.feature-card:hover{
    transform:translateY(-12px) scale(1.03);
}
.feature-card i{
    font-size:42px;
    margin-bottom:18px;
    background:var(--gradient);
    -webkit-background-clip:text;
    color:transparent;
}

/* FOOTER */
footer{
    padding:40px 0;
    text-align:center;
    opacity:.6;
}

/* RESPONSIVE */
@media(max-width:768px){
    .hero h1{font-size:2.5rem}
}
</style>
</head>

<body>

<!-- BACKGROUND BLOBS -->
<div class="blob one"></div>
<div class="blob two"></div>

<!-- HERO -->
<section class="hero container">
<div class="row align-items-center w-100">
    <div class="col-lg-6" data-aos="fade-right">
        <h1>Next-Gen <span>QR Attendance</span><br>Management System</h1>
        <p class="mt-3">
            Smart, secure and contactless attendance tracking for modern institutions.
        </p>

        <div class="d-flex gap-3 mt-4">
            <a href="{{ route('login') }}" class="btn-main">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </a>
            <a href="#features" class="btn-glass">
                Explore Features
            </a>
        </div>
    </div>

    <div class="col-lg-6 d-flex justify-content-center mt-5 mt-lg-0" data-aos="zoom-in">
        <div class="qr-card">
            <i class="fas fa-qrcode"></i>
        </div>
    </div>
</div>
</section>

<!-- FEATURES -->


<section class="features container" id="features">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Why QR Attendance?</h2>
        <p class="opacity-75">Designed for speed, security & simplicity</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="feature-card">
                <i class="fas fa-bolt"></i>
                <h5>Instant Scanning</h5>
                <p>Attendance marked in seconds using QR technology.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="feature-card">
                <i class="fas fa-user-shield"></i>
                <h5>Secure Access</h5>
                <p>Authenticated login with protected attendance records.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="feature-card">
                <i class="fas fa-chart-pie"></i>
                <h5>Smart Reports</h5>
                <p>Live analytics and downloadable attendance reports.</p>
            </div>
        </div>
    </div>
</section>



<footer>
    © {{ date('Y') }} QR Attendance System • Modern Education
</footer>

<!-- JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({
    duration:1000,
    once:true
});
</script>

</body>
</html>
