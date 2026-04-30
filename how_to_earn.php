<?php include 'navbar.php'; ?>

<section class="Rewards-hero">
    <h2>Join And Earn <span>Rewards</span></h2>
    <p>Earn Credits with every purchase and redeem them for discounts!</p>
    <button class="Rewards-btn" data-bs-toggle="modal" data-bs-target="#RewardsModal">Sign Up and Get Free
        Credits</button>
    <button class="Rewards-btn">Sign In</button>
</section>



    <div class="container-fluid p-0">

        <section class="rewards_section_padding rewards_bg_white text-center">
            <div class="container">
                <h2 class="fw-bold">How it Works?</h2>
                <p class="text-muted">Earn Credits with every purchase and redeem them for discounts!</p>
                <div class="row mt-5">
                    <div class="col-md-4">
                        <div class="rewards_step_circle">1</div>
                        <h5>Sign Up</h5>
                        <p class="small text-muted">Create an account on our store</p>
                    </div>
                    <div class="col-md-4">
                        <div class="rewards_step_circle">2</div>
                        <h5>Earn Credits</h5>
                        <p class="small text-muted">Earn Credits for shopping</p>
                    </div>
                    <div class="col-md-4">
                        <div class="rewards_step_circle">3</div>
                        <h5>Redeem</h5>
                        <p class="small text-muted">Redeem Credits for discounts</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="rewards_section_padding rewards_bg_light">
            <div class="container">
                <h2 class="text-center fw-bold">Ways To Earn</h2>
                <p class="text-center text-muted mb-5">Earn Credits with every purchase and redeem them for discounts!</p>

                <div class="row g-4 justify-content-center">
                    <div class="col-md-5 col-lg-4" data-bs-toggle="modal" data-bs-target="#rewardsJoinModal">
                        <div class="rewards_earn_card">
                            <div class="rewards_card_icon"><i class="fa-regular fa-pen-to-square"></i></div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Write A Review</h6>
                                <small class="text-muted">Get 15 Credits for leaving a text review... <i class="fa-solid fa-circle-info ms-1"></i></small>
                            </div>
                            <i class="fa-solid fa-chevron-right ms-2 opacity-50"></i>
                        </div>
                    </div>
                    <div class="col-md-5 col-lg-4" data-bs-toggle="modal" data-bs-target="#rewardsJoinModal">
                        <div class="rewards_earn_card">
                            <div class="rewards_card_icon"><i class="fa-solid fa-user"></i></div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Birthday Reward</h6>
                                <small class="text-muted">Get 100 Credits <i class="fa-solid fa-circle-info ms-1"></i></small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 col-lg-4" data-bs-toggle="modal" data-bs-target="#rewardsJoinModal">
                        <div class="rewards_earn_card">
                            <div class="rewards_card_icon"><i class="fa-solid fa-bolt"></i></div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Order Placed</h6>
                                <small class="text-muted">Get 1 Credit for every ₹10 spent <i class="fa-solid fa-circle-info ms-1"></i></small>
                            </div>
                            <i class="fa-solid fa-chevron-right ms-2 opacity-50"></i>
                        </div>
                    </div>
                    <div class="col-md-5 col-lg-4" data-bs-toggle="modal" data-bs-target="#rewardsJoinModal">
                        <div class="rewards_earn_card">
                            <div class="rewards_card_icon"><i class="fa-solid fa-share-nodes"></i></div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Refer & Earn</h6>
                                <small class="text-muted">Get 100 Credits <i class="fa-solid fa-circle-info ms-1"></i></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="rewards_section_padding rewards_bg_light border-top">
            <div class="container text-center">
                <h2 class="fw-bold">Referral Program</h2>
                <p class="text-muted">Give your friends a reward and claim your own when they make a purchase.</p>

                <div class="row justify-content-center mt-4">
                    <div class="col-md-4 col-10">
                        <div class="rewards_ref_box">
                            <small class="text-muted">They get</small>
                            <h4 class="fw-bold">₹100 Off Coupon</h4>
                            <img src="https://cdn-icons-png.flaticon.com/512/4213/4213958.png" class="rewards_gift_img" alt="gift">
                        </div>
                    </div>
                    <div class="col-md-4 col-10">
                        <div class="rewards_ref_box">
                            <small class="text-muted">You get</small>
                            <h4 class="fw-bold">100 Credits</h4>
                            <div class="mt-2">🟡🟡</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 small d-flex justify-content-center align-items-center gap-2">
                    <span class="badge bg-dark rounded-circle">1</span> Share link ➜
                    <span class="badge bg-dark rounded-circle">2</span> Friend buys ➜
                    <span class="badge bg-dark rounded-circle">3</span> You both get rewards
                </div>
            </div>
        </section>

        <section class="rewards_section_padding rewards_bg_light">
            <div class="container" style="max-width: 800px;">
                <h2 class="text-center fw-bold">Referral Leaderboard</h2>
                <p class="text-center text-muted">See who's earning the most rewards and climb the leaderboard!</p>

                <div class="d-flex justify-content-center my-4">
                    <div class="btn-group border rounded bg-white p-1">
                        <button class="btn btn-sm rewards_filter_btn" onclick="updateLeaderboard('week', this)">Week</button>
                        <button class="btn btn-sm rewards_filter_btn active" onclick="updateLeaderboard('month', this)">Month</button>
                        <button class="btn btn-sm rewards_filter_btn" onclick="updateLeaderboard('year', this)">Year</button>
                    </div>
                </div>

                <div class="rewards_table_container border">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Name</th>
                                <th class="text-end pe-4">Total Referrals</th>
                            </tr>
                        </thead>
                        <tbody id="leaderboard_body">
                            <tr>
                                <td class="ps-4">
                                    <span class="rewards_avatar" style="background: #fff3cd; color: #856404;">H</span> Harsh Sharma
                                </td>
                                <td class="text-end pe-4">1</td>
                            </tr>
                            <tr>
                                <td class="ps-4">
                                    <span class="rewards_avatar" style="background: #e2e3e5; color: #383d41;">A</span> Akshata jagtap
                                </td>
                                <td class="text-end pe-4">1</td>
                            </tr>
                            <tr>
                                <td class="ps-4">
                                    <span class="rewards_avatar" style="background: #d4edda; color: #155724;">J</span> Jenifer Catherin Lisiya
                                </td>
                                <td class="text-end pe-4">1</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    </div>

    <div class="modal fade" id="rewardsJoinModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rewards_modal_content shadow-lg">
                <div class="text-end">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <h2 class="fw-bold mb-3">Join And Earn Rewards</h2>
                    <p class="text-muted mb-4 px-3">Earn Credits with every purchase and redeem them for discounts!</p>
                    <button class="rewards_btn_dark w-100 mb-3 py-3 fs-5">Sign Up and Get Free Credits</button>
                    <p class="text-muted">Already have an account? <a href="#" class="text-dark fw-bold">Sign in</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sample Data for Leaderboard
        const leaderboardData = {
            week: [{
                    name: "Rahul Kumar",
                    referrals: 5,
                    color: "#fff3cd",
                    text: "#856404"
                },
                {
                    name: "Sneha Reddy",
                    referrals: 3,
                    color: "#e2e3e5",
                    text: "#383d41"
                },
                {
                    name: "Vijay Singh",
                    referrals: 2,
                    color: "#d4edda",
                    text: "#155724"
                }
            ],
            month: [{
                    name: "Harsh Sharma",
                    referrals: 12,
                    color: "#fff3cd",
                    text: "#856404"
                },
                {
                    name: "Akshata jagtap",
                    referrals: 10,
                    color: "#e2e3e5",
                    text: "#383d41"
                },
                {
                    name: "Jenifer Catherin Lisiya",
                    referrals: 8,
                    color: "#d4edda",
                    text: "#155724"
                }
            ],
            year: [{
                    name: "Mani Malladi",
                    referrals: 150,
                    color: "#fff3cd",
                    text: "#856404"
                },
                {
                    name: "Kiran Dev",
                    referrals: 120,
                    color: "#e2e3e5",
                    text: "#383d41"
                },
                {
                    name: "Priya Darshini",
                    referrals: 95,
                    color: "#d4edda",
                    text: "#155724"
                }
            ]
        };

        function updateLeaderboard(type, btn) {
            // 1. Update Active Button Class
            document.querySelectorAll('.rewards_filter_btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // 2. Get the table body
            const tbody = document.getElementById('leaderboard_body');

            // 3. Clear current content
            tbody.innerHTML = '';

            // 4. Loop through data and add rows
            leaderboardData[type].forEach(item => {
                const initial = item.name.charAt(0);
                const row = `
                <tr>
                    <td class="ps-4">
                        <span class="rewards_avatar" style="background: ${item.color}; color: ${item.text};">${initial}</span> ${item.name}
                    </td>
                    <td class="text-end pe-4">${item.referrals}</td>
                </tr>
            `;
                tbody.innerHTML += row;
            });
        }
    </script>
</body>

</html>

<?php include 'footer.php'; ?>