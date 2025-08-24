<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Driver Reviews — {{ $driver->name ?? 'Driver' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        /* Reset & base */
        * { margin:0; padding:0; box-sizing:border-box; }
        :root{
            --bg1:#667eea; --bg2:#764ba2;
            --card-bg: rgba(255,255,255,0.96);
            --text:#2c3e50; --muted:#6b7280; --accent:#4f46e5; --success:#10b981;
        }
        body{
            font-family: system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji","Segoe UI Emoji";
            background: linear-gradient(135deg, var(--bg1), var(--bg2));
            color:#fff;
            min-height:100vh;
            overflow-x:hidden;
        }

        /* Floating background orbs */
        .bg-shapes{ position:fixed; inset:0; z-index:-1; overflow:hidden; }
        .shape{ position:absolute; border-radius:50%; filter: blur(30px); opacity:.25; animation: float 9s ease-in-out infinite; }
        .shape.s1{ width:240px;height:240px; top:8%; left:6%; background:#ffffff; animation-delay:0s;}
        .shape.s2{ width:320px;height:320px; bottom:10%; right:8%; background:#93c5fd; animation-delay:1.2s;}
        .shape.s3{ width:180px;height:180px; top:50%; left:55%; background:#fef08a; animation-delay:.6s;}
        @keyframes float{
            0%,100%{ transform:translateY(0) rotate(0deg); }
            50%{ transform:translateY(-20px) rotate(10deg); }
        }

        /* Header */
        .container{ max-width:1100px; margin:0 auto; padding:32px 20px 60px; }
        .topbar{
            display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:24px;
        }
        .back-link{
            display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,.15);
            padding:10px 14px; border-radius:999px; color:#fff; text-decoration:none; font-weight:600;
            border:1px solid rgba(255,255,255,.25); transition:.2s;
        }
        .back-link:hover{ transform: translateY(-1px); box-shadow:0 10px 25px rgba(0,0,0,.15); }

        /* Driver header card */
        .driver-card{
            background: var(--card-bg); color:var(--text); border-radius:20px; padding:22px;
            box-shadow: 0 18px 50px rgba(0,0,0,.15); display:flex; gap:18px; align-items:center;
            border:1px solid rgba(255,255,255,.35);
        }
        .avatar{
            width:84px; height:84px; border-radius:16px; object-fit:cover; box-shadow:0 10px 30px rgba(0,0,0,.2);
        }
        .driver-meta h1{
            font-size:1.5rem; margin-bottom:6px; line-height:1.15;
        }
        .badges{ display:flex; flex-wrap:wrap; gap:10px; margin-top:6px; }
        .badge{
            display:inline-flex; gap:6px; align-items:center; font-weight:600; font-size:.9rem;
            padding:8px 12px; border-radius:999px; background:#eef2ff; color:#3730a3;
            border:1px solid #e0e7ff;
        }
        .badge.green{ background:#ecfdf5; color:#065f46; border-color:#d1fae5; }

        /* Section title */
        .section-title{
            margin:28px 2px 14px; font-weight:800; letter-spacing:.2px; opacity:.95;
        }

        /* Reviews grid */
        .reviews{
            display:grid; grid-template-columns: repeat(auto-fill,minmax(280px,1fr));
            gap:24px;
        }

        /* Review card */
        .review-card{
            background: var(--card-bg); color:var(--text); border-radius:18px; padding:18px 18px 16px;
            box-shadow: 0 14px 40px rgba(0,0,0,.14);
            border:1px solid rgba(255,255,255,.35);
            opacity:0; transform: translateY(24px) scale(.98);
            animation: fadeInUp .6s forwards;
        }
        /* stagger via style attr set in JS */

        @keyframes fadeInUp{
            to{ opacity:1; transform: translateY(0) scale(1); }
        }

        /* subtle fade-out on hover */
        .review-card:hover{ transition:.3s; opacity:.85; }

        .rev-header{ display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:8px; }
        .rev-author{ font-weight:700; font-size:1rem; }
        .rev-date{ color:var(--muted); font-size:.9rem; }

        /* Stars */
        .stars{ display:inline-flex; gap:2px; }
        .star{ width:18px; height:18px; display:inline-block; clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%); background:#e5e7eb; }
        .star.filled{ background: #f59e0b; }

        .rev-body{ color:#374151; line-height:1.55; margin-top:8px; }
        .rev-footer{ display:flex; gap:10px; margin-top:14px; }
        .chip{ font-size:.85rem; padding:6px 10px; border-radius:999px; background:#f1f5f9; color:#0f172a; border:1px solid #e2e8f0; }

        /* Empty state */
        .empty{
            background: rgba(255,255,255,.18); border:1px dashed rgba(255,255,255,.55); color:#fff;
            border-radius:16px; padding:28px; text-align:center;
        }

        /* Responsive */
        @media (max-width:640px){
            .driver-card{ flex-direction:column; align-items:flex-start; }
            .avatar{ width:100%; height:180px; border-radius:14px; }
        }
    </style>
</head>
<body>
    <!-- Fancy background blobs -->
    <div class="bg-shapes">
        <div class="shape s1"></div>
        <div class="shape s2"></div>
        <div class="shape s3"></div>
    </div>

    <div class="container">
        <div class="topbar">
            <a class="back-link" href="{{ url('/cars/find_driver') }}">← Back to Drivers</a>
        </div>

        <!-- Driver Header -->
        <section class="driver-card">
            <img class="avatar" src="{{ isset($driver->image) ? asset($driver->image) : 'https://via.placeholder.com/300x200?text=Driver' }}"
                 alt="{{ $driver->name ?? 'Driver' }}">
            <div class="driver-meta">
                <h1>{{ $driver->name ?? 'Driver' }}</h1>
                <div class="badges">
                    @if(!empty($driver->license_type))
                        <span class="badge">🪪 License: {{ $driver->license_type }}</span>
                    @endif
                    @if(!empty($driver->rating))
                        <span class="badge green">⭐ Rating: {{ $driver->rating }}</span>
                    @endif
                    @if(!empty($driver->address))
                        <span class="badge">📍 {{ $driver->address }}</span>
                    @endif
                    @if(!empty($driver->phone))
                        <span class="badge">📞 {{ $driver->phone }}</span>
                    @endif
                </div>
            </div>
        </section>

        <h2 class="section-title">Reviews</h2>

        @php
            // Normalize $reviews to a collection
            $reviews = collect($reviews ?? []);
        @endphp

        @if($reviews->isEmpty())
            <div class="empty">No reviews yet for this driver.</div>
        @else
            <div id="reviewsGrid" class="reviews">
                @foreach($reviews as $i => $review)
                    @php
                        // Try to be robust with columns that may or may not exist
                        $author = $review->reviewer_name ?? $review->user_name ?? $review->name ?? 'Anonymous';
                        $comment = $review->comment ?? $review->review ?? $review->message ?? '';
                        $rating = (int)($review->rating ?? $review->stars ?? 0);
                        $created = $review->created_at ?? null;
                        try{
                            $dateText = $created ? \Carbon\Carbon::parse($created)->format('M d, Y') : '';
                        } catch (\Exception $e) {
                            $dateText = $created;
                        }
                        // Optional tags/labels if present
                        $tag = $review->tag ?? $review->title ?? null;
                    @endphp

                    <article class="review-card" style="animation-delay: {{ ($i * 90) }}ms;">
                        <header class="rev-header">
                            <div class="rev-author">{{ $author }}</div>
                            <div class="rev-date">{{ $dateText }}</div>
                        </header>

                        @if($rating > 0)
                            <div class="stars" aria-label="Rating {{ $rating }} out of 5">
                                @for($s=1;$s<=5;$s++)
                                    <span class="star {{ $s <= $rating ? 'filled' : '' }}"></span>
                                @endfor
                            </div>
                        @endif>

                        <p class="rev-body">
                            {{ $comment }}
                        </p>

                        <footer class="rev-footer">
                            @if($tag)
                                <span class="chip">#{{ $tag }}</span>
                            @endif
                            @if(isset($review->trip_type))
                                <span class="chip">{{ ucfirst($review->trip_type) }}</span>
                            @endif
                            @if(isset($review->city))
                                <span class="chip">📍 {{ $review->city }}</span>
                            @endif
                        </footer>
                    </article>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        // Staggered fade-in on scroll using IntersectionObserver
        (function(){
            const cards = document.querySelectorAll('.review-card');
            if(!('IntersectionObserver' in window) || cards.length === 0) return;

            const io = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if(entry.isIntersecting){
                        entry.target.style.animationPlayState = 'running';
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12 });

            cards.forEach(card => {
                card.style.animationPlayState = 'paused'; // start paused, play when visible
                io.observe(card);
            });
        })();

        // Gentle parallax on background blobs
        document.addEventListener('mousemove', (e) => {
            const cx = e.clientX / window.innerWidth - .5;
            const cy = e.clientY / window.innerHeight - .5;
            document.querySelectorAll('.shape').forEach((el, idx) => {
                const speed = (idx + 1) * 6;
                el.style.transform = `translate(${cx * speed}px, ${cy * speed}px)`;
            });
        });
    </script>
</body>
</html>
