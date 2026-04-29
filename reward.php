<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHD Rewards Widget</title>
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> -->

  <style>
    /* Floating Button */
    .rewards-btn {
      position: fixed;
      bottom: 20px;
      left: 20px;
      background-color: #0a2b4a;
      border: 2px solid white;
      /* background-color: #b31d2c; */
      color: white;
      padding: 10px 25px;
      border-radius: 50px;
      cursor: pointer;
      z-index: 1000;
      /* border: none; */
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      font-weight: 600;
    }

    /* The Rewards Card */
    .rewards-card {
      position: fixed;
      bottom: 85px;
      left: 20px;
      width: 350px;
      height: 550px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
      display: none;
      flex-direction: column;
      z-index: 1001;
      border: 1px solid #ddd;
      overflow: hidden;
    }

    /* Header Styling */
    .rewards-header {
      /* background-color: #000; */
      background-color: #0a2b4a;
      color: #fff;
      padding: 20px;
      position: relative;
    }

    .close-rewards {
      position: absolute;
      right: 15px;
      top: 15px;
      cursor: pointer;
      font-size: 1.2rem;
      opacity: 0.8;
    }

    .back-btn {
      cursor: pointer;
      margin-right: 12px;
      font-size: 1.1rem;
      display: none;
    }

    .rewards-body {
      overflow-y: auto;
      padding: 15px;
      flex-grow: 1;
      position: relative;
    }

    /* Views Handling */
    .view-section {
      display: none;
      flex-direction: column;
    }

    .view-section.active {
      display: flex;
    }

    /* UI Elements */
    .promo-section {
      border: 1px solid #eee;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      margin-bottom: 15px;
    }

    .btn-signup {
      background-color: #0a2b4a;
      color: #fff !important;
      border-radius: 6px;
      padding: 10px;
      font-weight: 600;
      width: 100%;
      display: block;
      text-decoration: none;
      border: none;
    }

    .list-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px;
      border-top: 1px solid #f0f0f0;
      cursor: pointer;
    }

    .list-item:hover {
      background-color: #f9f9f9;
    }

    /* Referral Box */
    .referral-box {
      border: 1px solid #eee;
      border-radius: 10px;
      margin-top: 10px;
      overflow: hidden;
      cursor: pointer;
    }

    .referral-item {
      display: flex;
      align-items: center;
      padding: 12px 15px;
      border-top: 1px solid #eee;
    }

    .referral-item i {
      font-size: 1.2rem;
      margin-right: 15px;
      color: #444;
    }

    /* Login Overlay Style (Image 5b3259) */
    #loginOverlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: #000;
      z-index: 1005;
      display: none;
      color: white;
      padding: 40px 20px;
      text-align: center;
    }

    /* Added Back Arrow for Login Popup */
    .login-back-btn {
      position: absolute;
      left: 15px;
      top: 15px;
      cursor: pointer;
      font-size: 1.2rem;
      color: #fff;
    }

    .login-card {
      background: white;
      border-radius: 12px;
      padding: 25px 15px;
      color: #333;
      margin-top: 30px;
    }

    /* Custom Scrollbar */
    .rewards-body::-webkit-scrollbar {
      width: 5px;
    }

    .rewards-body::-webkit-scrollbar-thumb {
      background: #ddd;
      border-radius: 10px;
    }
  </style>
</head>

<body>

  <button id="rewardsBtn" class="rewards-btn" onclick="toggleRewards(event)">
    <i class="fas fa-gift me-2"></i> REWARDS
  </button>

  <div id="rewardsCard" class="rewards-card">

    <div class="rewards-header" id="headerArea">
      <span class="close-rewards" onclick="closeRewards()"><i class="fas fa-times"></i></span>
      <span id="backBtn" class="back-btn" onclick="showView('home')"><i class="fas fa-arrow-left"></i></span>
      <span id="headerTitle" class="fw-bold">Welcome to /PHD/</span>
    </div>

    <div class="rewards-body">

      <div id="viewHome" class="view-section active">
        <div class="promo-section">
          <h6 class="fw-bold">Join And Earn Rewards</h6>
          <p class="small text-muted">Earn Credits with every purchase and redeem them for discounts!</p>
          <button class="btn-signup" onclick="showLogin()">Sign Up and Get Free Credits</button>
          <p class="small mt-2 mb-0">Already have an account? <a href="#" onclick="showLogin()"
              class="text-dark fw-bold">Sign in</a></p>
        </div>

        <div class="list-item" onclick="showView('earn')">
          <span><i class="fas fa-users-cog me-2"></i> Ways To Earn</span>
          <i class="fas fa-chevron-right text-muted small"></i>
        </div>
        <div class="list-item" onclick="showView('redeem')">
          <span><i class="fas fa-hand-holding-heart me-2"></i> Ways To Redeem</span>
          <i class="fas fa-chevron-right text-muted small"></i>
        </div>

        <div class="referral-box" onclick="showLogin()">
          <div class="p-3 text-center">
            <i class="fas fa-gift mb-2" style="font-size: 1.8rem;"></i>
            <h6 class="fw-bold">Referral Program</h6>
            <p class="small text-muted mb-0">Give your friends a reward and claim your own.</p>
          </div>
          <div class="referral-item">
            <i class="fas fa-hand-holding-usd"></i>
            <div class="small">
              <div>They get</div>
              <div class="fw-bold">₹100 Off Coupon</div>
            </div>
          </div>
          <div class="referral-item">
            <i class="fas fa-coins"></i>
            <div class="small">
              <div>You get</div>
              <div class="fw-bold">100 Credits</div>
            </div>
          </div>
        </div>
      </div>

      <div id="viewEarn" class="view-section">
        <div class="list-item border-0">
          <div class="d-flex align-items-center">
            <i class="fas fa-edit me-3"></i>
            <div><b>Write A Review</b><br><small class="text-muted">Get 15 Credits for a text review.</small></div>
          </div>
        </div>
        <div class="list-item border-0">
          <div class="d-flex align-items-center">
            <i class="fas fa-birthday-cake me-3"></i>
            <div><b>Birthday Reward</b><br><small class="text-muted">Get 100 Credits</small></div>
          </div>
        </div>
        <div class="list-item border-0">
          <div class="d-flex align-items-center">
            <i class="fas fa-shopping-bag me-3"></i>
            <div><b>Order Placed</b><br><small class="text-muted">1 Credit for every ₹10 spent</small></div>
          </div>
        </div>
        <div class="list-item border-0">
          <div class="d-flex align-items-center">
            <i class="fas fa-share-alt me-3"></i>
            <div><b>Refer and Earn</b><br><small class="text-muted">Get 100 Credits</small></div>
          </div>
        </div>
        <div class="mt-auto p-2">
          <button class="btn-signup" onclick="showLogin()">Sign Up and Get Free Credits</button>
        </div>
      </div>

      <div id="viewRedeem" class="view-section">
        <div class="text-center py-5">
          <p class="text-muted">We did not find any ways to redeem rules yet.</p>
        </div>
        <div class="mt-auto p-2">
          <button class="btn-signup" onclick="showLogin()">Sign Up and Get Free Credits</button>
        </div>
      </div>

      <div id="loginOverlay">
        <span class="login-back-btn" onclick="hideLogin()"><i class="fas fa-arrow-left"></i></span>
        <span class="close-rewards" onclick="closeRewards()"><i class="fas fa-times"></i></span>
        <div>
          <h3 class="fw-bold">/PHD/</h3>
          <p class="small">Powered by <b>KwikPass</b></p>
          <h5 class="mt-4 fw-bold">Login now to avail best offers!</h5>
        </div>
        <div class="login-card">
          <div class="input-group mb-3 border rounded p-1">
            <span class="input-group-text bg-white border-0"><img src="https://flagcdn.com/w20/in.png" width="18">
              +91</span>
            <input type="text" class="form-control border-0" placeholder="Enter Mobile Number">
          </div>
          <div class="form-check small text-start mb-3">
            <input class="form-check-input" type="checkbox" id="notify" checked>
            <label class="form-check-label text-muted" for="notify">Notify me with offers & updates</label>
          </div>
          <p style="font-size: 11px; color: #999;">
            I accept that I have read & understood your <a href="#" class="text-muted">Privacy Policy</a> and <a
              href="#" class="text-muted">T&Cs</a>.
          </p>
        </div>
      </div>

    </div>
  </div>

  <script>
    const card = document.getElementById("rewardsCard");
    const btn = document.getElementById("rewardsBtn");
    const backBtn = document.getElementById("backBtn");
    const headerTitle = document.getElementById("headerTitle");

    function toggleRewards(event) {
      event.stopPropagation();
      if (card.style.display === "flex") {
        closeRewards();
      } else {
        card.style.display = "flex";
        showView('home');
      }
    }

    function closeRewards() {
      card.style.display = "none";
      hideLogin();
    }

    function showView(view) {
      // Hide all views
      document.querySelectorAll('.view-section').forEach(v => v.classList.remove('active'));

      if (view === 'home') {
        document.getElementById('viewHome').classList.add('active');
        headerTitle.innerText = "Welcome to /PHD/";
        backBtn.style.display = "none";
      } else if (view === 'earn') {
        document.getElementById('viewEarn').classList.add('active');
        headerTitle.innerText = "Ways To Earn";
        backBtn.style.display = "inline-block";
      } else if (view === 'redeem') {
        document.getElementById('viewRedeem').classList.add('active');
        headerTitle.innerText = "Ways To Redeem";
        backBtn.style.display = "inline-block";
      }
    }

    function showLogin() {
      document.getElementById('loginOverlay').style.display = "block";
    }

    function hideLogin() {
      document.getElementById('loginOverlay').style.display = "none";
    }

    // Outside click to close
    window.onclick = function (event) {
      if (!card.contains(event.target) && event.target !== btn && !btn.contains(event.target)) {
        closeRewards();
      }
    };
  </script>
</body>

</html>