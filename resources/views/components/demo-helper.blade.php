<!-- Floating Demo Helper Button -->
<div id="demo-helper-btn" title="Petunjuk Uji Coba Fitur (Demo Accounts)" style="position: fixed; bottom: 85px; left: 25px; z-index: 99999; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); box-shadow: 0 4px 15px rgba(220,53,69,0.4); color: #fff; font-size: 24px; transition: all 0.3s ease;">
    <i class="fa fa-info-circle"></i>
</div>

<!-- Modal/Popover for Role & Account Info -->
<div id="demo-helper-card" style="display: none; position: fixed; bottom: 155px; left: 25px; z-index: 99999; width: 320px; background: rgba(20, 20, 20, 0.95); border: 1px solid rgba(220, 53, 69, 0.3); border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); padding: 20px; color: #fff; font-family: 'Poppins', sans-serif;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid rgba(225,225,225,0.1); padding-bottom: 8px;">
        <h6 style="margin: 0; font-weight: 700; color: #dc3545; font-size: 1rem;"><i class="fa fa-user-shield"></i> Akun Uji Coba (Demo)</h6>
        <span id="close-demo-card" style="cursor: pointer; color: #999; font-size: 18px; line-height: 1;">&times;</span>
    </div>
    <p style="font-size: 0.8rem; color: #aaa; margin-bottom: 15px; line-height: 1.4;">Klik tombol <strong>"Gunakan"</strong> untuk otomatis mengisi form login, atau klik email untuk menyalin.</p>
    
    <!-- Admin Info -->
    <div class="demo-account-item" style="background: rgba(220,53,69,0.05); border: 1px solid rgba(220,53,69,0.15); padding: 10px; border-radius: 5px; margin-bottom: 10px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
            <strong style="font-size: 0.85rem; color: #dc3545;">Role: Admin</strong>
            <button onclick="fillDemoLogin('admin@ride62.com', 'admin123')" class="btn-demo-fill" style="background: #dc3545; color: #fff; border: none; font-size: 0.75rem; padding: 2px 8px; border-radius: 3px; cursor: pointer; font-weight: 600;">Gunakan</button>
        </div>
        <div style="font-size: 0.8rem; color: #ddd; word-break: break-all;">
            <div style="margin-bottom: 2px;">Email: <span class="copyable" onclick="copyText('admin@ride62.com', this)" style="cursor:pointer; text-decoration:underline;" title="Klik untuk menyalin">admin@ride62.com</span> <i class="fa-regular fa-copy" style="font-size: 10px; opacity: 0.7;"></i></div>
            <div>Password: <code>admin123</code></div>
        </div>
    </div>

    <!-- Seller Info -->
    <div class="demo-account-item" style="background: rgba(59,130,246,0.05); border: 1px solid rgba(59,130,246,0.15); padding: 10px; border-radius: 5px; margin-bottom: 10px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
            <strong style="font-size: 0.85rem; color: #3b82f6;">Role: Seller</strong>
            <button onclick="fillDemoLogin('seller@ride62.com', 'seller123')" class="btn-demo-fill" style="background: #3b82f6; color: #fff; border: none; font-size: 0.75rem; padding: 2px 8px; border-radius: 3px; cursor: pointer; font-weight: 600;">Gunakan</button>
        </div>
        <div style="font-size: 0.8rem; color: #ddd; word-break: break-all;">
            <div style="margin-bottom: 2px;">Email: <span class="copyable" onclick="copyText('seller@ride62.com', this)" style="cursor:pointer; text-decoration:underline;" title="Klik untuk menyalin">seller@ride62.com</span> <i class="fa-regular fa-copy" style="font-size: 10px; opacity: 0.7;"></i></div>
            <div>Password: <code>seller123</code></div>
        </div>
    </div>

    <!-- Buyer Info -->
    <div class="demo-account-item" style="background: rgba(16,185,129,0.05); border: 1px solid rgba(16,185,129,0.15); padding: 10px; border-radius: 5px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
            <strong style="font-size: 0.85rem; color: #10b981;">Role: Buyer</strong>
            <button onclick="fillDemoLogin('buyer@ride62.com', 'buyer123')" class="btn-demo-fill" style="background: #10b981; color: #fff; border: none; font-size: 0.75rem; padding: 2px 8px; border-radius: 3px; cursor: pointer; font-weight: 600;">Gunakan</button>
        </div>
        <div style="font-size: 0.8rem; color: #ddd; word-break: break-all;">
            <div style="margin-bottom: 2px;">Email: <span class="copyable" onclick="copyText('buyer@ride62.com', this)" style="cursor:pointer; text-decoration:underline;" title="Klik untuk menyalin">buyer@ride62.com</span> <i class="fa-regular fa-copy" style="font-size: 10px; opacity: 0.7;"></i></div>
            <div>Password: <code>buyer123</code></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const helperBtn = document.getElementById('demo-helper-btn');
        const helperCard = document.getElementById('demo-helper-card');
        const closeBtn = document.getElementById('close-demo-card');

        if (helperBtn && helperCard) {
            helperBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                if (helperCard.style.display === 'none' || helperCard.style.display === '') {
                    helperCard.style.display = 'block';
                    helperBtn.style.transform = 'rotate(180deg) scale(0.9)';
                } else {
                    helperCard.style.display = 'none';
                    helperBtn.style.transform = 'rotate(0) scale(1)';
                }
            });

            closeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                helperCard.style.display = 'none';
                helperBtn.style.transform = 'rotate(0) scale(1)';
            });

            document.addEventListener('click', function(e) {
                if (helperCard.style.display === 'block' && !helperCard.contains(e.target) && e.target !== helperBtn && !helperBtn.contains(e.target)) {
                    helperCard.style.display = 'none';
                    helperBtn.style.transform = 'rotate(0) scale(1)';
                }
            });
        }
        
        // Auto-fill from URL params (if redirected from another page)
        const urlParams = new URLSearchParams(window.location.search);
        const email = urlParams.get('demo_email');
        const password = urlParams.get('demo_password');
        
        if (email && password) {
            const emailInput = document.querySelector('input[type="email"]');
            const passwordInput = document.querySelector('input[type="password"]');
            if (emailInput && passwordInput) {
                emailInput.value = email;
                passwordInput.value = password;
                emailInput.dispatchEvent(new Event('input', { bubbles: true }));
                passwordInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }
    });

    function fillDemoLogin(email, password) {
        const emailInput = document.querySelector('input[type="email"]');
        const passwordInput = document.querySelector('input[type="password"]');
        
        if (emailInput && passwordInput) {
            emailInput.value = email;
            passwordInput.value = password;
            
            // Dispatch events
            emailInput.dispatchEvent(new Event('input', { bubbles: true }));
            passwordInput.dispatchEvent(new Event('input', { bubbles: true }));
            
            // Focus on password to show action
            passwordInput.focus();
            
            // Show custom alert if SweetAlert2 is loaded
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Form login otomatis terisi!',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    background: '#1a1a1a',
                    color: '#fff',
                    iconColor: '#dc3545'
                });
            } else {
                alert('Form login otomatis terisi!');
            }
        } else {
            // Redirect to login page with parameters
            window.location.href = "{{ route('login') }}?demo_email=" + encodeURIComponent(email) + "&demo_password=" + encodeURIComponent(password);
        }
    }

    function copyText(text, element) {
        navigator.clipboard.writeText(text).then(function() {
            const originalHTML = element.innerHTML;
            element.style.color = '#10b981';
            element.innerText = 'Tersalin!';
            setTimeout(function() {
                element.style.color = '';
                element.innerHTML = originalHTML;
            }, 1200);
        }).catch(function() {
            // Fallback copy
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            try {
                document.execCommand('copy');
                const originalHTML = element.innerHTML;
                element.style.color = '#10b981';
                element.innerText = 'Tersalin!';
                setTimeout(function() {
                    element.style.color = '';
                    element.innerHTML = originalHTML;
                }, 1200);
            } catch (err) {
                console.error('Failed to copy', err);
            }
            document.body.removeChild(textarea);
        });
    }
</script>
