<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHD Rewards & Build</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <style>
    /* Leaderboard */
    .build-phd-table-container {
      background: #fff;
      border: 1px solid #eee;
      border-radius: 8px;
      overflow: hidden;
    }

    .build-phd-avatar {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-right: 10px;
      font-weight: bold;
    }

    /* Form Styles */
    .build-phd-form-label {
      font-size: 0.9rem;
      font-weight: 500;
      margin-bottom: 5px;
      color: #555;
    }

    .build-phd-input {
      border-radius: 4px;
      border: 1px solid #ccc;
      padding: 10px;
      margin-bottom: 20px;
    }

    .build-phd-input:focus {
      border-color: #000;
      box-shadow: none;
    }

    .build-phd-submit-btn {
      background: #333;
      color: #fff;
      border: none;
      padding: 12px;
      width: 100%;
      font-weight: bold;
    }

    /* Modal Split Layout */
    .build-phd-modal-left {
      background: #000;
      color: #fff;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      text-align: center;
    }

    .build-phd-modal-right {
      padding: 40px;
    }
  </style>
</head>

<body>



  <section class="build-phd-section-padding border-top">
    <div class="container" style="max-width: 800px;">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
          <li class="breadcrumb-item"><a href="#" class="text-dark">Home</a></li>
          <li class="breadcrumb-item active">Let's Build @ /PHD/</li>
        </ol>
      </nav>
      <h2 class="text-center fw-bold">Let's Build @ /PHD/</h2>
      <p class="text-center text-muted small px-md-5 mt-3">We are excited to hear from you. What do you love about our
        products? Please fill this form to help us create the best products for your skin.</p>

      <form class="mt-5" method="POST" action="send_mail.php">

        <label class="build-phd-form-label">Name*</label>
        <input type="text" name="name" class="form-control build-phd-input">

        <label class="build-phd-form-label">Email</label>
        <input type="email" name="email" class="form-control build-phd-input">

        <label class="build-phd-form-label">number</label>
        <input type="number" name="number" class="form-control build-phd-input">

        <label class="build-phd-form-label">What is your skin type?</label>
        <select name="skin_type" class="form-select build-phd-input">
          <option selected disabled>Please Select</option>
          <option value="Normal">Normal</option>
          <option value="Oily">Oily</option>
          <option value="Dry">Dry</option>
          <option value="Combination">Combination</option>
          <option value="Normal">Normal</option>
          <option value="Sensitive">Sensitive</option>
        </select>

        <label class="build-phd-form-label">What concerns have you struggled with recently?</label>
        <select name="concerns" class="form-select build-phd-input">
          <option selected disabled>Please Select</option>
          <option value="Acne">Acne</option>
          <option value="Pigmentation">Pigmentation</option>
          <option value="Barrier damage">Barrier damage</option>
          <option value="Dryness/dehydration">Dryness/dehydration</option>
          <option value="Sensitivity/reactivity">Sensitivity/reactivity</option>
          <option value="Oiliness">Oiliness</option>
          <option value="Texture issues (roughness, bumps)">Texture issues (roughness, bumps)</option>
          <option value="Aging signs (fine lines, uneven tone)">Aging signs (fine lines, uneven tone)</option>
          <option value="Other">Other</option>
        </select>

        <label class="build-phd-form-label d-block mb-3">And when you tried to solve these concerns, where did skincare
          let you down?</label>
        <div class="form-check mb-2">

          <input class="form-check-input" type="checkbox" name="issues[]" value="Doesn't solve problems fully"
            id="check1">
          <label class="form-check-label small" for="check1">Doesn't solve problems fully</label> <br>

          <input class="form-check-input" type="checkbox" name="issues[]" value="Causes irritation / sensitivity"
            id="check2">
          <label class="form-check-label small" for="check2">Causes irritation / sensitivity</label> <br>

          <input class="form-check-input" type="checkbox" name="issues[]" value="Overcomplicates routines" id="check3">
          <label class="form-check-label small" for="check3">Overcomplicates routines</label><br>

          <input class="form-check-input" type="checkbox" name="issues[]"
            value="Feels made for someone else’s skin, not mine" id="check4">
          <label class="form-check-label small" for="check4">Feels made for someone else’s skin, not mine</label><br>

          <input class="form-check-input" type="checkbox" name="issues[]" value="Marketing says more than it delivers"
            id="check5">
          <label class="form-check-label small" for="check5">Marketing says more than it delivers</label><br>

          <input class="form-check-input" type="checkbox" name="issues[]" value="Other" id="check6">
          <label class="form-check-label small" for="check6">Other</label>

        </div>

        <label class="build-phd-form-label mt-4">If /PHD/ could create one perfect solution for you, what would it
          solve?</label>
        <textarea name="solution" class="form-control build-phd-input" rows="4"></textarea>

        <label class="build-phd-form-label">What matters to you when choosing skincare?</label>
        <select name="priority" class="form-select build-phd-input">
          <option selected disabled>Please Select</option>
          <option value="Clinically proven results">Clinically proven results</option>
          <option value="Barrier safety (no irritation)">Barrier safety (no irritation)</option>
          <option value="Works with my skin over time">Works with my skin over time</option>
          <option value="Immediate and visible improvement">Immediate and visible improvement</option>
        </select>

        <label class="build-phd-form-label mt-4">Is there a skincare doubt or question you wish brands answered better?
        </label>
        <textarea name="doubt" class="form-control build-phd-input" rows="4"></textarea>

        <label class="build-phd-form-label d-block mt-3">Would you like to be a part of /PHD/'s future product trials
          and early testing?</label>
        <div class="d-flex gap-3 mt-2 mb-4">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="trial" value="Yes" id="yes">
            <label class="form-check-label small" for="yes">Yes</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="trial" value="No" id="no">
            <label class="form-check-label small" for="no">No</label>
          </div>
        </div>

        <button type="submit" class="build-phd-submit-btn">Submit</button>
      </form>
    </div>
  </section>

  <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content border-0 overflow-hidden" style="border-radius: 15px;">
        <div class="row g-0">
          <div class="col-md-6 build-phd-modal-left">
            <h3 class="fw-bold">/PHD/</h3>
            <p class="small opacity-75 mt-5">Login now to avail best offers!</p>
          </div>
          <div class="col-md-6 build-phd-modal-right position-relative">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
            <div class="mt-4">
              <div class="input-group mb-3 border rounded p-1">
                <span class="input-group-text bg-white border-0"><img src="https://flagcdn.com/w20/in.png" width="20">
                  +91</span>
                <input type="text" class="form-control border-0 shadow-none" placeholder="Enter Mobile Number">
              </div>
              <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="notify">
                <label class="form-check-label small text-muted" for="notify">Notify me with offers & updates</label>
              </div>
              <p class="text-center small text-muted">I accept that I have read & understood your <a href="#"
                  class="text-dark">Privacy Policy</a> and <a href="#" class="text-dark">T&Cs</a>.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>