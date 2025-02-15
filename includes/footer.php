            </div> <!-- /#content -->
        </div> <!-- /.wrapper -->
        
        <!-- Profile Modal -->
        <div class="modal fade" id="profileModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">โปรไฟล์</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="profileForm">
                            <div class="mb-3">
                                <label class="form-label">ชื่อผู้ใช้</label>
                                <input type="text" class="form-control" value="<?php echo $_SESSION['username'] ?? ''; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">รหัสผ่านใหม่</label>
                                <input type="password" class="form-control" name="new_password">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                                <input type="password" class="form-control" name="confirm_password">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">บันทึก</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
        <script src="assets/js/admin.js"></script>

        <!-- Facebook SDK -->
        <div id="fb-root"></div>
        <script>
            window.fbAsyncInit = function() {
                FB.init({
                    xfbml: true,
                    version: 'v18.0'
                });
            };

            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = 'https://connect.facebook.net/th_TH/sdk/xfbml.customerchat.js';
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>

        <!-- Messenger Chat Plugin -->
        <div class="fb-customerchat"
            attribution="biz_inbox"
            page_id="<?php echo FACEBOOK_PAGE_ID; ?>"
            theme_color="#ffc107"
            greeting_dialog_display="show"
            greeting_dialog_delay="5"
            logged_in_greeting="สวัสดีค่ะ 😊 มีอะไรให้เราช่วยไหมคะ?"
            logged_out_greeting="สวัสดีค่ะ 😊 มีอะไรให้เราช่วยไหมคะ?">
        </div>

        <!-- Other footer content -->
        <footer class="bg-light py-4 mt-5">
            <div class="container">
                <div class="row">
                    <!-- ... existing footer content ... -->
                </div>
            </div>
        </footer>
    </body>
</html>