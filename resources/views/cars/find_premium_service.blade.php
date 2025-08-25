<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Emergency Mechanics - Premium Service</title>
<style>
/* --- Global Styles --- */
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Arial', sans-serif; overflow-x: hidden; transition: all 0.5s ease; position: relative; background: linear-gradient(135deg, #667eea, #764ba2); }
h1,h2,h3,p { margin:0; }
button { cursor:pointer; }
a { text-decoration:none; }
/* --- Floating Shapes --- */
.bg-shapes { position: fixed; top:0; left:0; width:100%; height:100%; z-index:-1; overflow:hidden; }
.shape { position:absolute; border-radius:50%; background: rgba(255,255,255,0.1); animation: float 6s infinite ease-in-out; }
.shape:nth-child(1){width:100px;height:100px;top:10%;left:10%; animation-delay:0s;}
.shape:nth-child(2){width:150px;height:150px;top:60%;right:10%; animation-delay:2s;}
.shape:nth-child(3){width:80px;height:80px;bottom:10%;left:50%; animation-delay:4s;}
@keyframes float{0%,100%{transform:translateY(0) rotate(0deg);opacity:0.7;}50%{transform:translateY(-20px) rotate(180deg);opacity:1;}}

/* --- Header --- */
.header{ text-align:center; padding:50px 20px; color:white; }
.header h1{ font-size:3rem; text-shadow:2px 2px 8px rgba(0,0,0,0.3); animation:slideInDown 1s ease-out; }
.header p{ font-size:1.2rem; opacity:0.9; animation:slideInUp 1s ease-out 0.2s both; }

/* --- Mechanics List --- */
.mechanics-container{ max-width:1200px; margin:0 auto; padding:20px; display:flex; flex-direction:column; gap:15px; }
.mechanic-card{ background: rgba(255,255,255,0.15); border-radius:15px; backdrop-filter: blur(10px); border:1px solid rgba(255,255,255,0.2); padding:20px; display:flex; flex-direction:column; transition: transform 0.3s ease, box-shadow 0.3s ease; }
.mechanic-card:hover{ transform: translateY(-5px); box-shadow:0 10px 30px rgba(255,255,255,0.2); }
.mechanic-info{ display:flex; justify-content:space-between; flex-wrap:wrap; }
.mechanic-left{ display:flex; flex-direction:column; gap:5px; }
.mechanic-name{ font-size:1.4rem; font-weight:bold; color:#fff; }
.mechanic-detail{ font-size:0.95rem; color:#eee; }
.view-services-btn{ align-self:flex-end; margin-top:10px; padding:10px 20px; border:none; border-radius:12px; background: linear-gradient(135deg,#3498db,#2980b9); color:white; font-weight:bold; transition:all 0.3s ease; }
.view-services-btn:hover{ background: linear-gradient(135deg,#2980b9,#3498db); transform: translateY(-2px); box-shadow:0 5px 15px rgba(52,152,219,0.4); }

/* --- Modal --- */
.modal{ display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.9); backdrop-filter:blur(5px); justify-content:center; align-items:center; z-index:9999; overflow-y:auto; }
.modal-content{ max-width:800px; width:90%; background:white; border-radius:20px; padding:30px; position:relative; animation:slideInUp 0.5s ease-out; }
.close-btn{ position:absolute; top:15px; right:20px; font-size:2rem; border:none; background:none; cursor:pointer; color:#7f8c8d; transition:color 0.3s; }
.close-btn:hover{ color:#e74c3c; }
.modal h2{ color:#2c3e50; margin-bottom:20px; text-align:center; }
.service-list{ display:flex; flex-direction:column; gap:10px; margin-bottom:20px; }
.service-item{ display:flex; justify-content:space-between; padding:10px 15px; border-radius:12px; border:1px solid #e9ecef; cursor:pointer; transition:all 0.3s ease; }
.service-item.selected{ background:#d5f4e6; border-color:#27ae60; }
.payment-options{ display:flex; gap:15px; flex-wrap:wrap; margin-bottom:20px; }
.payment-option{ flex:1; min-width:120px; border:2px solid #e9ecef; border-radius:15px; padding:15px; text-align:center; cursor:pointer; transition:all 0.3s ease; }
.payment-option.selected{ border-color:#27ae60; background:#d5f4e6; }
.confirm-btn{ width:100%; background:linear-gradient(135deg,#27ae60,#2ecc71); color:white; padding:15px; border:none; border-radius:12px; font-size:1.1rem; font-weight:bold; cursor:pointer; transition:all 0.3s ease; }
.confirm-btn:hover{ background:linear-gradient(135deg,#2ecc71,#27ae60); transform:translateY(-2px); box-shadow:0 5px 15px rgba(46,204,113,0.3); }
.confirm-btn:disabled{ background:#bdc3c7; cursor:not-allowed; transform:none; box-shadow:none; }

/* --- Animations --- */
@keyframes slideInDown{0%{transform:translateY(-50px);opacity:0;}100%{transform:translateY(0);opacity:1;}}
@keyframes slideInUp{0%{transform:translateY(50px);opacity:0;}100%{transform:translateY(0);opacity:1;}}

/* --- Responsive --- */
@media(max-width:768px){ .mechanic-card{flex-direction:column;} .view-services-btn{align-self:center;} }
</style>
</head>
<body>
<!-- Floating Shapes -->
<div class="bg-shapes"><div class="shape"></div><div class="shape"></div><div class="shape"></div></div>

<!-- Header -->
<div class="header">
<h1>🛠️ Emergency Mechanics</h1>
<p>Find the best mechanics instantly</p>
</div>

<!-- Mechanics List -->
<div class="mechanics-container" id="mechanicsList">
<div class="loading" style="color:white;text-align:center;">Loading mechanics...</div>
</div>

<!-- Modal for Services & Payment -->
<div class="modal" id="servicesModal">
<div class="modal-content">
<button class="close-btn" onclick="closeModal()">&times;</button>
<div id="modalBody">
<!-- Dynamic content -->
</div>
</div>
</div>

<script>
// Floating shapes interactivity
document.addEventListener('mousemove', e=>{
const shapes=document.querySelectorAll('.shape');
const mouseX=e.clientX/window.innerWidth;
const mouseY=e.clientY/window.innerHeight;
shapes.forEach((s,i)=>{
let speed=(i+1)*0.5;
s.style.transform=`translate(${mouseX*speed}px,${mouseY*speed}px)`;
});
});

// Global state
let mechanics=[], services=[], selectedServices=[], selectedPayment=null, currentMechanic=null;

// Fetch mechanics
async function loadMechanics(){
try{
const res=await fetch('http://127.0.0.1:8000/mechanics');
mechanics=await res.json();
const container=document.getElementById('mechanicsList');
container.innerHTML='';
mechanics.forEach((m,i)=>{
const card=document.createElement('div');
card.className='mechanic-card';
card.innerHTML=`
<div class="mechanic-info">
<div class="mechanic-left">
<div class="mechanic-name">${m.name}</div>
<div class="mechanic-detail">Specialty: ${m.specialty}</div>
<div class="mechanic-detail">Address: ${m.address}</div>
<div class="mechanic-detail">Phone: ${m.phone}</div>
<div class="mechanic-detail">Rating: ${m.rating}</div>
</div>
</div>
<button class="view-services-btn" onclick="openServices(${m.id})">🔧 View Services</button>
`;
container.appendChild(card);
});
}catch(err){console.error(err);document.getElementById('mechanicsList').innerHTML='<div style="color:white;text-align:center;">⚠️ Unable to load mechanics. Retry later.</div>';}
}

// Open services modal
async function openServices(mechanicId){
currentMechanic=mechanicId;
selectedServices=[]; selectedPayment=null;
try{
const res=await fetch('http://127.0.0.1:8000/car_services');
services=await res.json();
showServiceSelection();
document.getElementById('servicesModal').style.display='flex';
document.body.style.overflow='hidden';
}catch(err){console.error(err);}
}

// Show service selection
function showServiceSelection(){
const modalBody=document.getElementById('modalBody');
let html=`<h2>🔧 Select Services</h2><div class="service-list">`;
services.forEach(s=>{
html+=`<div class="service-item" data-id="${s.id}" onclick="toggleService(${s.id})">${s.service_name} - $${s.price}</div>`;
});
html+=`</div><button class="confirm-btn" onclick="showPayment()" ${selectedServices.length? '':'disabled'}>Confirm & Proceed</button>`;
modalBody.innerHTML=html;
updateServiceButtons();
}

// Toggle service selection
function toggleService(id){
const idx=selectedServices.indexOf(id);
if(idx>-1){selectedServices.splice(idx,1);}else{selectedServices.push(id);}
updateServiceButtons();
}

function updateServiceButtons(){
const items=document.querySelectorAll('.service-item');
items.forEach(it=>{
const id=parseInt(it.getAttribute('data-id'));
if(selectedServices.includes(id)){it.classList.add('selected');}else{it.classList.remove('selected');}
});
const btn=document.querySelector('.confirm-btn');
if(btn) btn.disabled=selectedServices.length===0;
}

// Show payment
function showPayment(){
const modalBody=document.getElementById('modalBody');
let html=`<h2>💳 Payment</h2><div class="payment-options">
<div class="payment-option" data-pay="cash" onclick="selectPayment('cash')">Cash</div>
<div class="payment-option" data-pay="bkash" onclick="selectPayment('bkash')">bKash</div>
<div class="payment-option" data-pay="nagad" onclick="selectPayment('nagad')">Nagad</div>
</div>
<button class="confirm-btn" id="payConfirmBtn" onclick="confirmPayment()" disabled>Confirm Payment</button>`;
modalBody.innerHTML=html;
}

// Select payment
function selectPayment(method){
selectedPayment=method;
document.querySelectorAll('.payment-option').forEach(p=>p.classList.remove('selected'));
document.querySelector(`.payment-option[data-pay="${method}"]`).classList.add('selected');
document.getElementById('payConfirmBtn').disabled=false;
}

// Confirm payment
function confirmPayment(){
if(!selectedPayment) return;
let selectedServicesNames=services.filter(s=>selectedServices.includes(s.id)).map(s=>s.service_name);
alert(`🎉 Payment Confirmed!\nMechanic: ${mechanics.find(m=>m.id===currentMechanic).name}\nServices: ${selectedServicesNames.join(', ')}\nPayment Method: ${selectedPayment.toUpperCase()}`);
closeModal();
}

// Close modal
function closeModal(){
document.getElementById('servicesModal').style.display='none';
document.body.style.overflow='auto';
}

// Initial load
loadMechanics();
</script>
</body>
</html>
