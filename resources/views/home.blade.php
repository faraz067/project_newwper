@extends('layouts.app')

@section('content')
 
<header class="header-2">
  <div class="page-header min-vh-75 relative" style="background-image: url('{{ asset('assets/img/bg-landing.jpg') }}')">
    <span class="mask bg-gradient-dark opacity-4"></span>
    <div class="container">
      <div class="row">
        <div class="col-lg-7 text-center mx-auto">
          <h1 class="text-white font-weight-black pt-3 mt-n5">Lapangin</h1>
          <p class="lead text-white mt-3">Solusi booking lapangan olahraga cepat, praktis, dan terjadwal.<br/> Atur jadwal bermainmu dengan sistem booking lapangan yang efisien. </p>
        </div>
      </div>
    </div>
  </div>
</header>

<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-n6">

<section class="pt-3 pb-4" id="count-stats">
  <div class="container">
    <div class="row">
      <div class="col-lg-9 mx-auto py-3">
        <div class="row">
          <div class="col-md-4 position-relative">
            <div class="p-3 text-center">
              <h1 class="text-gradient text-dark"><span id="state1" countTo="70">0</span>+</h1>
              <h5 class="mt-3">Coded Elements</h5>
              <p class="text-sm font-weight-normal">From buttons, to inputs, navbars, alerts or cards, you are covered</p>
            </div>
            <hr class="vertical dark">
          </div>
          <div class="col-md-4 position-relative">
            <div class="p-3 text-center">
              <h1 class="text-gradient text-dark"> <span id="state2" countTo="15">0</span>+</h1>
              <h5 class="mt-3">Design Blocks</h5>
              <p class="text-sm font-weight-normal">Mix the sections, change the colors and unleash your creativity</p>
            </div>
            <hr class="vertical dark">
          </div>
          <div class="col-md-4">
            <div class="p-3 text-center">
              <h1 class="text-gradient text-dark" id="state3" countTo="4">0</h1>
              <h5 class="mt-3">Pages</h5>
              <p class="text-sm font-weight-normal">Save 3-4 weeks of work when you use our pre-made pages for your website</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<section class="my-5 py-5">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-4 ms-auto me-auto p-lg-4 mt-lg-0 mt-4">
        <div class="rotating-card-container">
          <div class="card card-rotate card-background card-background-mask-primary shadow-dark mt-md-0 mt-5">
            <div class="front front-background" style="background-image: url(https://images.unsplash.com/photo-1569683795645-b62e50fbf103?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=987&q=80); background-size: cover;">
              <div class="card-body py-7 text-center">
                <i class="material-symbols-rounded text-white text-4xl my-3">touch_app</i>
                <h3 class="text-white">Feel the <br /> Material Kit</h3>
                <p class="text-white opacity-8">All the Bootstrap components that you need in a development have been re-design with the new look.</p>
              </div>
            </div>
            <div class="back back-background" style="background-image: url(https://images.unsplash.com/photo-1498889444388-e67ea62c464b?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1365&q=80); background-size: cover;">
              <div class="card-body pt-7 text-center">
                <h3 class="text-white">Discover More</h3>
                <p class="text-white opacity-8"> You will save a lot of time going from prototyping to full-functional code because all elements are implemented.</p>
                <a href=".//sections/page-sections/hero-sections.html" target="_blank" class="btn btn-white btn-sm w-50 mx-auto mt-3">Start with Headers</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6 ms-auto">
        <div class="row justify-content-start">
          <div class="col-md-6">
            <div class="info">
              <i class="material-symbols-rounded text-gradient text-success text-3xl">content_copy</i>
              <h5 class="font-weight-bolder mt-3">Full Documentation</h5>
              <p class="pe-5">Built by developers for developers. Check the foundation and you will find everything inside our documentation.</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info">
              <i class="material-symbols-rounded text-gradient text-success text-3xl">flip_to_front</i>
              <h5 class="font-weight-bolder mt-3">Bootstrap 5 Ready</h5>
              <p class="pe-3">The world’s most popular front-end open source toolkit, featuring Sass variables and mixins.</p>
            </div>
          </div>
        </div>
        <div class="row justify-content-start mt-5">
          <div class="col-md-6 mt-3">
            <i class="material-symbols-rounded text-gradient text-success text-3xl">price_change</i>
            <h5 class="font-weight-bolder mt-3">Save Time & Money</h5>
            <p class="pe-5">Creating your design from scratch with dedicated designers can be very expensive. Start with our Design System.</p>
          </div>
          <div class="col-md-6 mt-3">
            <div class="info">
              <i class="material-symbols-rounded text-gradient text-success text-3xl">devices</i>
              <h5 class="font-weight-bolder mt-3">Fully Responsive</h5>
              <p class="pe-3">Regardless of the screen size, the website content will naturally fit the given resolution.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<section class="my-5 py-5">
  <div class="container">
    <div class="row">
      <div class="row justify-content-center text-center my-sm-5">
        <div class="col-lg-6">
          <span class="badge bg-success mb-3">Infinite combinations</span>
          <h2 class="text-dark mb-0">Huge collection of sections</h2>
          <p class="lead">We have created multiple options for you to put together and customise into pixel perfect pages. </p>
        </div>
      </div>
    </div>
  </div>
  <div class="container mt-sm-5 mt-3">
    <div class="row">
      <div class="col-lg-3">
        <div class="position-sticky pb-lg-5 pb-3 mt-lg-0 mt-5 ps-2" style="top: 100px">
          <h3>Elements</h3>
          <h6 class="text-secondary font-weight-normal pe-3">70+ carefully crafted small elements that come with multiple colors and shapes. These are only a few of them.</h6>
        </div>
      </div>

<!-- Control Center for Material UI Kit: parallax effects, scripts for the example pages etc -->
<!--  Google Maps Plugin    -->

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTTfWur0PDbZWPr7Pmq8K3jiDp0_xUziI"></script>
<script src="./assets/js/material-kit.min.js?v=3.1.0" type="text/javascript"></script>


<script type="text/javascript">

  if (document.getElementById('state1')) {
    const countUp = new CountUp('state1', document.getElementById("state1").getAttribute("countTo"));
    if (!countUp.error) {
      countUp.start();
    } else {
      console.error(countUp.error);
    }
  }
  if (document.getElementById('state2')) {
    const countUp1 = new CountUp('state2', document.getElementById("state2").getAttribute("countTo"));
    if (!countUp1.error) {
      countUp1.start();
    } else {
      console.error(countUp1.error);
    }
  }
  if (document.getElementById('state3')) {
    const countUp2 = new CountUp('state3', document.getElementById("state3").getAttribute("countTo"));
    if (!countUp2.error) {
      countUp2.start();
    } else {
      console.error(countUp2.error);
    };
  }
</script>

@endsection