@extends('front.gamefront')

@section('title', 'Cart')

@section('Content')
<style>
    body {
        background-color: #1b2838;
        color: #c7d5e0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .cart-container {
        max-width: 900px;
        margin: 60px auto;
        padding: 30px;
        background-color: #2a475e;
        border-radius: 12px;
    }

    .cart-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 30px;
        padding: 20px;
        background-color: #1b2838;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    }

    .cart-item img {
        width: 120px;
        height: auto;
        border-radius: 10px;
        margin-right: 20px;
    }

    .item-info {
        flex: 1;
        text-align: left;
    }

    .item-info h4 {
        margin: 0;
        font-size: 20px;
    }

    .item-info p {
        margin: 4px 0;
        font-size: 14px;
        color: #aaa;
    }

    .buy-btn {
        background-color: #66c0f4;
        border: none;
        padding: 12px 24px;
        font-size: 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s ease;
        color: #1b2838;
    }

    .buy-btn:hover {
        background-color: #00bcd4;
    }

    .remove-btn {
        background: transparent;
        border: none;
        color: #ff6b6b;
        font-size: 14px;
        cursor: pointer;
        margin-top: 10px;
    }

    /* Receipt Modal */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.9);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-box {
        background: #1b2838;
        border: 2px solid #66c0f4;
        border-radius: 12px;
        padding: 30px;
        width: 500px;
        max-width: 95%;
        color: #c7d5e0;
        box-shadow: 0 0 30px rgba(0,0,0,0.8);
        animation: popIn 0.3s ease;
    }

    @keyframes popIn {
        0% { transform: scale(0.7); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    .modal-header {
        border-bottom: 2px solid #66c0f4;
        padding-bottom: 10px;
        text-align: center;
    }
    .modal-header h3 {
        margin: 0;
        font-size: 24px;
        color: #66c0f4;
        font-weight: bold;
        text-shadow: 0 0 8px #66c0f4;
    }

    .bill-item {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        padding: 10px;
        background: #2a475e;
        border-radius: 10px;
        box-shadow: inset 0 0 5px rgba(0,0,0,0.5);
    }
    .bill-item img {
        width: 70px;
        height: 70px;
        border-radius: 8px;
        margin-right: 15px;
        border: 1px solid #66c0f4;
    }
    .bill-details h4 {
        margin: 0;
        font-size: 18px;
        color: #fff;
    }
    .bill-details p {
        margin: 2px 0;
        font-size: 14px;
        color: #aaa;
    }

    .bill-meta {
        font-size: 14px;
        color: #8f98a0;
        margin-bottom: 12px;
        text-align: center;
    }

    .bill-total {
        border-top: 2px solid #66c0f4;
        padding-top: 12px;
        margin-top: 15px;
        font-size: 18px;
        font-weight: bold;
        color: #66c0f4;
        text-align: right;
        text-shadow: 0 0 5px #66c0f4;
    }

    .bill-footer {
        margin-top: 12px;
        font-size: 13px;
        color: #8f98a0;
        text-align: center;
        font-style: italic;
    }

    .modal-ok-btn {
        margin-top: 20px;
        padding: 12px 0;
        border: none;
        border-radius: 8px;
        background: #66c0f4;
        color: #1b2838;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.3s;
        display: block;
        width: 100%;
    }
    .modal-ok-btn:hover {
        background: #00bcd4;
    }
</style>

<div class="cart-container">
    <h2>Your Cart</h2>

    @if($cartItems->isEmpty())
        <p>Your cart is empty.</p>
    @else
        @php $grandTotal = 0; @endphp
        @foreach($cartItems as $item)
            @php
                $quantity = $item->quantity ?? 1;
                $total = $item->price * $quantity;
                $grandTotal += $total;
            @endphp
            <div class="cart-item">
                <img src="{{ $item->game_image }}" alt="{{ $item->game_title }}">
                <div class="item-info">
                    <h4>{{ $item->game_title }}</h4>
                    <p>Price: ₹{{ $item->price }} × {{ $quantity }}</p>
                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="remove-btn">Remove</button>
                    </form>
                </div>
                <button class="buy-btn rzp-button"
                        data-id="{{ $item->id }}"
                        data-name="{{ $item->game_title }}"
                        data-price="{{ $total }}"
                        data-img="{{ $item->game_image }}">
                    Buy Now
                </button>
            </div>
        @endforeach

        <hr>
        <h4>Total: ₹{{ $grandTotal }}</h4>

        @if($cartItems->isNotEmpty())
            <button id="rzr-pay-btn" class="buy-btn" style="margin-top: 20px;">Checkout</button>
        @endif
    @endif
</div>

<!-- Hidden Razorpay form for form submission -->
<form id="razorpay-form" action="{{ route('cart.checkout') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
</form>

<!-- Bill Modal -->
<div class="modal-overlay" id="paymentModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Purchase Receipt</h3>
        </div>
        <div class="bill-meta" id="billDate"></div>
        <div id="billContent"></div>
        <div class="bill-total" id="billTotal"></div>
        <div class="bill-footer">Thank you for your purchase on Steam Clone</div>
        <button class="modal-ok-btn" id="modalOkBtn">OK</button>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let modal = document.getElementById("paymentModal");
    let billContent = document.getElementById("billContent");
    let billTotal = document.getElementById("billTotal");
    let billDate = document.getElementById("billDate");
    let modalOkBtn = document.getElementById("modalOkBtn");
    let redirectUrl = "/library";

    // Calculate total amount for checkout
    let totalAmount = {{ $grandTotal ?? 0 }};

    // Store cart items data for receipt generation
    let cartItemsData = [
        @foreach($cartItems as $item)
        {
            id: {{ $item->id }},
            title: "{{ $item->game_title }}",
            image: "{{ $item->game_image }}",
            price: {{ $item->price * ($item->quantity ?? 1) }}
        },
        @endforeach
    ];

    // Ensure Razorpay is loaded before using it
    if (typeof Razorpay === 'undefined') {
        console.error('Razorpay checkout script failed to load');
        let checkoutBtn = document.getElementById('rzr-pay-btn');
        if (checkoutBtn) {
            checkoutBtn.addEventListener("click", function(e) {
                e.preventDefault();
                alert('Payment gateway is currently unavailable. Please try again later.');
            });
        }
        document.querySelectorAll(".rzp-button").forEach(function(btn) {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                alert('Payment gateway is currently unavailable. Please try again later.');
            });
        });
        return;
    }

    // Razorpay checkout handler for main checkout button
    document.getElementById('rzr-pay-btn')?.addEventListener('click', function (e) {
        e.preventDefault();
        if (typeof Razorpay === 'undefined') {
            alert('Payment gateway is loading or unavailable. Please refresh the page.');
            return;
        }
        try {
            var options = {
                "key": "{{ config('services.razorpay.key') }}",
                "amount": totalAmount * 100,
                "currency": "INR",
                "name": "Steam Store",
                "description": "Game Purchase",
                "handler": function (response) {
                    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                    document.getElementById('razorpay-form').submit();
                }
            };
            var rzp = new Razorpay(options);
            rzp.open();
        } catch (error) {
            console.error('Razorpay initialization error:', error);
            alert('Unable to initialize payment. Please refresh the page and try again.');
        }
    });

    document.querySelectorAll(".rzp-button").forEach(function(btn) {
        btn.addEventListener("click", function(e) {
            e.preventDefault();

            try {
                let id = this.getAttribute("data-id");
                let price = parseFloat(this.getAttribute("data-price"));
                let name = this.getAttribute("data-name");
                let img = this.getAttribute("data-img");

                let options = {
                    "key": "{{ config('services.razorpay.key') }}",
                    "amount": price * 100,
                    "currency": "INR",
                    "name": "Steam Gaming Store",
                    "description": name,
                    "image": img,
                    "handler": function(response) {
                        fetch("/cart/buy/" + id, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({ payment_id: response.razorpay_payment_id })
                        })
                        .then(res => res.json())
                        .then(data => {
                            let now = new Date().toLocaleString();
                            billDate.innerHTML = `Transaction Date: ${now}`;

                            // Build receipt dynamically using stored cart data
                            billContent.innerHTML = '';
                            let grandTotal = 0;
                            cartItemsData.forEach(function(item) {
                                grandTotal += item.price;
                                billContent.innerHTML += `
                                    <div class="bill-item">
                                        <img src="${item.image}" alt="${item.title}">
                                        <div class="bill-details">
                                            <h4>${item.title}</h4>
                                            <p>Order ID: ${response.razorpay_payment_id}</p>
                                            <p>Price Paid: ₹${item.price}</p>
                                        </div>
                                    </div>
                                `;
                            });
                            billTotal.innerHTML = `Total Paid: ₹${grandTotal}`;

                            modal.style.display = "flex";
                        })
                        .catch(err => {
                            console.error('Payment processing error:', err);
                            modal.style.display = "flex";
                            billContent.innerHTML = `<p style="color:red;">Transaction failed or receipt unavailable.</p>`;
                        });
                    },
                    "prefill": {
                        "name": "{{ auth()->check() ? auth()->user()->name : '' }}",
                        "email": "{{ auth()->check() ? auth()->user()->email : '' }}",
                        "contact": "9999999999"
                    },
                    "theme": { "color": "#3399cc" }
                };

                let rzp = new Razorpay(options);
                rzp.open();
            } catch (error) {
                console.error('Razorpay initialization error:', error);
                alert('Unable to initialize payment. Please refresh the page and try again.');
            }
        });
    });

    modalOkBtn.addEventListener("click", function() {
        modal.style.display = "none";
        window.location.href = redirectUrl;
    });
});
</script>
@endsection
