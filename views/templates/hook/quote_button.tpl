{*
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the terms of this License Agreement.
 *
 * @author    Syntax It <support@syntaxit.cl>
 * @copyright 2025 Syntax It
 * @license   Commercial License Agreement
*}

<div class="ps-request-quote-button-wrapper">
    {if $hide_buy_button}
    <style>
        .product-add-to-cart {
            display: none !important;
        }
    </style>
    {/if}

    <form id="ps-quote-request-form" class="ps-quote-request-form" method="post" action="{url entity='module' name='ps_request_quote' controller='quote' action='create'}">
        <div class="form-group">
            <input type="hidden" name="id_product" value="{$product_id}" />
            
            <label for="ps-quote-quantity" class="form-label">{l s='Quantity' d='Shop.Forms.Labels'}</label>
            <div class="input-group">
                <button class="btn btn-sm btn-outline-secondary" type="button" id="decrease-qty">
                    <i class="fa fa-minus"></i>
                </button>
                <input 
                    type="number" 
                    id="ps-quote-quantity" 
                    name="quantity" 
                    value="1" 
                    min="1" 
                    max="999999"
                    class="form-control text-center"
                />
                <button class="btn btn-sm btn-outline-secondary" type="button" id="increase-qty">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>

        <div class="form-group">
            <label for="ps-quote-message" class="form-label">{l s='Message (optional)' d='Modules.Psrequestquote'}</label>
            <textarea 
                id="ps-quote-message" 
                name="message" 
                class="form-control" 
                rows="3" 
                placeholder="{l s='Any special request or question?' d='Modules.Psrequestquote'}"
            ></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100">
            <i class="fa fa-envelope"></i>
            {$quote_button_text|escape:'html':'UTF-8'}
        </button>
    </form>
</div>

<style>
    .ps-request-quote-button-wrapper {
        margin-top: 20px;
        padding: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        background-color: #fafafa;
    }

    .ps-quote-request-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .ps-quote-request-form .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .ps-quote-request-form .form-label {
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .ps-quote-request-form .form-control {
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-family: inherit;
        font-size: 14px;
    }

    .ps-quote-request-form .form-control:focus {
        border-color: #0066cc;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
    }

    .ps-quote-request-form .input-group {
        display: flex;
        align-items: center;
        gap: 0;
    }

    .ps-quote-request-form .input-group button {
        padding: 10px 12px;
        border: 1px solid #ddd;
        background: white;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .ps-quote-request-form .input-group button:hover {
        background-color: #f5f5f5;
    }

    .ps-quote-request-form .input-group input {
        border-left: none;
        border-right: none;
        flex: 1;
        text-align: center;
    }

    .ps-quote-request-form .btn-primary {
        background-color: #0066cc;
        color: white;
        border: none;
        padding: 12px 20px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .ps-quote-request-form .btn-primary:hover {
        background-color: #0052a3;
    }

    .ps-quote-request-form .btn-primary:active {
        background-color: #003d7a;
    }

    @media (max-width: 768px) {
        .ps-request-quote-button-wrapper {
            padding: 15px;
            margin-top: 15px;
        }

        .ps-quote-request-form .input-group button {
            padding: 8px 10px;
            font-size: 12px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const qtyInput = document.getElementById('ps-quote-quantity');
        const decreaseBtn = document.getElementById('decrease-qty');
        const increaseBtn = document.getElementById('increase-qty');

        if (decreaseBtn) {
            decreaseBtn.addEventListener('click', function() {
                const current = parseInt(qtyInput.value);
                if (current > 1) {
                    qtyInput.value = current - 1;
                }
            });
        }

        if (increaseBtn) {
            increaseBtn.addEventListener('click', function() {
                const current = parseInt(qtyInput.value);
                qtyInput.value = current + 1;
            });
        }

        // Form submission handling
        const form = document.getElementById('ps-quote-request-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success';
                        alertDiv.textContent = data.message || 'Quote request sent successfully!';
                        alertDiv.style.marginBottom = '20px';
                        form.parentNode.insertBefore(alertDiv, form);
                        
                        // Reset form
                        form.reset();
                        qtyInput.value = 1;
                        
                        // Remove alert after 5 seconds
                        setTimeout(() => alertDiv.remove(), 5000);
                    } else {
                        alert(data.message || 'An error occurred. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });
        }
    });
</script>
