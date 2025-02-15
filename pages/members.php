<!-- Modal เพิ่มสมาชิก -->
<div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMemberModalLabel">เพิ่มสมาชิกใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addMemberForm">
                    <div class="mb-3">
                        <label for="memberName" class="form-label">ชื่อ-นามสกุล</label>
                        <input type="text" class="form-control" id="memberName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="memberEmail" class="form-label">อีเมล</label>
                        <input type="email" class="form-control" id="memberEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="memberPhone" class="form-label">เบอร์โทร</label>
                        <input type="tel" class="form-control" id="memberPhone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="memberAddress" class="form-label">ที่อยู่</label>
                        <textarea class="form-control" id="memberAddress" name="address" rows="3"></textarea>
                    </div>
                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">เพิ่มสมาชิก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 