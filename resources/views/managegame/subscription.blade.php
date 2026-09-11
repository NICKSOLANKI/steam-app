@extends('front.gamefront')

@section('title', 'Subscription')

@section('Content')
<style>
body {
    background-color: #1b2838;
    color: #c7d5e0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
.subscription-container {
    max-width: 700px;
    margin: 80px auto;
    padding: 40px;
    background-color: #2a475e;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 0 20px rgba(0,0,0,0.4);
}
.subscription-container h2 {
    font-size: 32px;
    margin-bottom: 25px;
}
.plan {
    margin-top: 30px;
    padding: 20px;
    border: 2px solid #66c0f4;
    border-radius: 10px;
    background-color: #1b2838;
}
.plan h3 {
    font-size: 24px;
    margin-bottom: 10px;
    color: #fff;
}
.plan p {
    font-size: 16px;
    color: #dcdfe3;
}
.price {
    font-size: 28px;
    font-weight: bold;
    color: #66c0f4;
    margin: 20px 0;
}
.plan button {
    background-color: #66c0f4;
    color: #1b2838;
    border: none;
    padding: 12px 30px;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease;
}
.plan button:hover {
    background-color: #00bcd4;
}
.countdown {
    font-size: 18px;
    margin-top: 15px;
    color: #fff;
}
</style>

<div class="subscription-container">
    <h2>Choose Your Subscription Plan</h2>
    
    @if(isset($userSubscription) && $userSubscription->status=='active')
        {{-- Current Active Subscription --}}
        <div class="plan" style="border-color: #28a745;">
            <h3>{{ ucfirst($userSubscription->plan) }} - Active</h3>
            <p>{{ $userSubscription->description }}</p>
            <div class="price">₹{{ $userSubscription->price }} 
                <span style="font-size:16px;">
                    @if($userSubscription->plan === 'lifetime')
                        / Lifetime
                    @else
                        / {{ $userSubscription->plan }}
                    @endif
                </span>
            </div>
            <button type="button" style="background-color:#28a745;" id="open-subscription-btn">Access Games</button>
            @if($userSubscription->plan !== 'lifetime' && $userSubscription->end_date)
                <div class="countdown" id="countdown"></div>
            @endif
        </div>
    @else
        {{-- Available Plans --}}
        @foreach($availablePlans as $plan)
            <div class="plan" data-plan-id="{{ $plan->id }}">
                <h3>{{ $plan->name }}</h3>
                <p>{{ $plan->description }}</p>
                <div class="price">{{ $plan->formatted_price }} 
                    <span style="font-size:16px;">
                        @if($plan->isLifetime())
                            / Lifetime
                        @else
                            / {{ $plan->duration_text }}
                        @endif
                    </span>
                </div>
                
                @if($plan->features)
                    <div style="text-align: left; margin: 15px 0;">
                        <strong>Features:</strong>
                        <ul style="margin: 5px 0; padding-left: 20px;">
                            @foreach($plan->features as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <button type="button" class="rzp-subscribe-btn" data-plan="{{ $plan->slug }}" data-price="{{ $plan->price }}" data-name="{{ $plan->name }}">
                    Choose {{ $plan->name }}
                </button>
            </div>
        @endforeach
    @endif
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const openBtn = document.getElementById('open-subscription-btn');
    const buyBtns = document.querySelectorAll('.rzp-subscribe-btn');

    // Handle active subscription
    @if(isset($userSubscription) && $userSubscription->status==='active')
        if(openBtn) {
            openBtn.onclick = () => { window.location.href="{{ route('subscription.open') }}"; };
        }

        @if($userSubscription->plan !== 'lifetime' && $userSubscription->end_date)
            const endTime = new Date("{{ $userSubscription->end_date }}").getTime();
            const countdownEl = document.getElementById('countdown');

            const interval = setInterval(() => {
                const now = new Date().getTime();
                const distance = endTime - now;

                if(distance < 0){
                    clearInterval(interval);
                    countdownEl.innerHTML="Subscription expired";
                    location.reload(); // Reload to show available plans
                    return;
                }

                const days = Math.floor(distance/(1000*60*60*24));
                const hours = Math.floor((distance%(1000*60*60*24))/(1000*60*60));
                const minutes = Math.floor((distance%(1000*60*60))/(1000*60));
                const seconds = Math.floor((distance%(1000*60))/1000);

                countdownEl.innerHTML=`Time remaining: ${days}d ${hours}h ${minutes}m ${seconds}s`;
            },1000);
        @endif
    @endif

    // Handle subscription purchase
    buyBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const planSlug = this.dataset.plan;
            const planPrice = parseFloat(this.dataset.price);
            const planName = this.dataset.name;
            
            var options = {
                "key":"{{ config('services.razorpay.key') }}",
                "amount": planPrice * 100,
                "currency":"INR",
                "name":"Steam Clone Store",
                "description": planName + " Subscription",
                "image":"{{ asset('asset/imagies/cyber2.jpg') }}",
                "handler":function(response){
                    fetch("{{ route('subscription.purchase') }}",{
                        method:"POST",
                        headers:{
                            "X-Requested-With":"XMLHttpRequest",
                            "X-CSRF-TOKEN":"{{ csrf_token() }}",
                            "Content-Type":"application/json"
                        },
                        body: JSON.stringify({
                            razorpay_payment_id: response.razorpay_payment_id,
                            plan: planSlug
                        })
                    })
                    .then(res=>res.json())
                    .then(data=>{
                        if(data.success){
                            alert(data.message);
                            location.reload(); // Reload to show active subscription
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Payment processing failed. Please try again.');
                    });
                },
                "prefill":{
                    "name":"{{ auth()->user()->name }}",
                    "email":"{{ auth()->user()->email }}",
                    "contact":"9999999999"
                },
                "theme":{"color":"#3399cc"}
            };

            var rzp = new Razorpay(options);
            rzp.open();
        });
    });
});
</script>
@endsection
