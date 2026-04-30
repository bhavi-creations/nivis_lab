<?php include 'navbar.php'; ?>
<img src="./assets/img/refer&earn.png" alt="" class="img-fluid">

<div class="refer_earn_container">
    <p>Give your friends a reward and claim your own when they make a purchase.</p>

    <div class="refer_earn_card_row">
        <div class="refer_earn_reward_card">
            <div class="refer_earn_card_label">They get</div>
            <div class="refer_earn_card_value">₹100 Off Coupon</div>
            <img src="https://cdn-icons-png.flaticon.com/512/4213/4213958.png" class="refer_earn_gift_img">
        </div>
        <div class="refer_earn_reward_card">
            <div class="refer_earn_card_label">You get</div>
            <div class="refer_earn_card_value">100 Credits</div>
            <div style="font-size: 20px;">🟡🟡</div>
        </div>
    </div>

    <button class="refer_earn_signup_btn" data-bs-toggle="modal" data-bs-target="#loginModal">Sign Up and Get Free Credits</button>
    <div>Already have an account? <a href="#" class="text-dark fw-bold">Sign in</a></div>

    <div class="mt-4 small">
        <span class="badge bg-dark rounded-circle">1</span> Share link &nbsp;
        <span class="badge bg-dark rounded-circle">2</span> Friend buys &nbsp;
        <span class="badge bg-dark rounded-circle">3</span> You both get rewards
    </div>

    <div class="refer_earn_leaderboard_section">
        <h2 class="refer_earn_title">Referral Leaderboard</h2>
        <p>See who's earning the most rewards and climb the leaderboard!</p>

        <div class="refer_earn_filter_group">
            <button class="refer_earn_filter_btn active" onclick="filterLeaderboard('week', this)">Week</button>
            <button class="refer_earn_filter_btn" onclick="filterLeaderboard('month', this)">Month</button>
            <button class="refer_earn_filter_btn" onclick="filterLeaderboard('year', this)">Year</button>
        </div>

        <div class="refer_earn_table_container">
            <table class="refer_earn_table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th class="text-end">Total Referrals</th>
                    </tr>
                </thead>
                <tbody id="leaderboard_body">
                    <tr>
                        <td><span class="refer_earn_avatar" style="background:#fff3cd; color:#856404;">H</span> Harsh Sharma</td>
                        <td class="text-end">1</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content refer_earn_modal_content shadow-lg">
            <div class="refer_earn_modal_body">
                <div class="refer_earn_modal_left">
                    <h3 class="fw-bold">/PHD/</h3>
                    <p class="small">PROVEN HONEST DERMA</p>
                    <div class="mt-3 small opacity-75">Powered by <span class="fw-bold">KwikPass ⚡</span></div>
                    <h4 class="mt-5 fw-bold">Login now to avail best offers!</h4>
                </div>

                <div class="refer_earn_modal_right">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>

                    <div class="refer_earn_input_group">
                        <div class="refer_earn_country_code">
                            <img src="https://flagcdn.com/w20/in.png" width="20" alt="India">
                            <span>+91</span>
                        </div>
                        <input type="text" class="refer_earn_input" placeholder="Enter Mobile Number">
                    </div>

                    <div class="d-flex align-items-start gap-2 mb-4">
                        <input type="checkbox" id="notify" class="mt-1">
                        <label for="notify" class="refer_earn_checkbox_text">Notify me with offers & updates</label>
                    </div>

                    <div class="text-center mt-3">
                        <p class="refer_earn_checkbox_text">I accept that I have read & understood your <br> <a href="#" class="text-dark">Privacy Policy</a> and <a href="#" class="text-dark">T&Cs</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Leaderboard Filter Logic
    const data = {
        week: [{
            name: "Harsh Sharma",
            count: 1,
            color: "#fff3cd",
            text: "#856404"
        }],
        month: [{
                name: "Harsh Sharma",
                count: 12,
                color: "#fff3cd",
                text: "#856404"
            },
            {
                name: "Akshata jagtap",
                count: 10,
                color: "#e2e3e5",
                text: "#383d41"
            },
            {
                name: "Jenifer Catherin Lisiya",
                count: 8,
                color: "#d4edda",
                text: "#155724"
            }
        ],
        year: [{
            name: "Mani Malladi",
            count: 150,
            color: "#fff3cd",
            text: "#856404"
        }]
    };

    function filterLeaderboard(type, btn) {
        document.querySelectorAll('.refer_earn_filter_btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const tbody = document.getElementById('leaderboard_body');
        tbody.innerHTML = '';

        data[type].forEach(item => {
            const row = `<tr>
                <td><span class="refer_earn_avatar" style="background:${item.color}; color:${item.text};">${item.name.charAt(0)}</span> ${item.name}</td>
                <td class="text-end">${item.count}</td>
            </tr>`;
            tbody.innerHTML += row;
        });
    }
</script>

<?php include 'footer.php'; ?>