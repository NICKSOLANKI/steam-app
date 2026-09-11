@extends('front.gamefront')

@section('title','Community Chat')

@section('Content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
/* ===== Community Chat Styling ===== */
.community-chat {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
    background-color: #0e1a2b;
    color: #E6E6E6;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding: 25px;
    height: 85vh;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}
.channels {
    background: linear-gradient(180deg,#16202D 0%,#132232 100%);
    border-radius: 10px;
    padding: 20px;
    overflow-y: auto;
    border: 1px solid #2a3f5a;
}
.channel-group h3 {
    color: #66C0F4;
    font-size: 1.1rem;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid rgba(42,63,90,0.5);
    display: flex;
    align-items: center;
}
.channel {
    padding: 10px 12px;
    border-radius: 6px;
    margin-bottom: 6px;
    cursor: pointer;
    color: #c5d3e0;
    transition: all .2s;
}
.channel::before {content:"#";margin-right:6px;opacity:.6;}
.channel.active {
    background: rgba(102,192,244,.15);
    color:#66C0F4;
    font-weight:500;
    border-left:3px solid #66C0F4;
}
.chat-container {
    display:flex;
    flex-direction:column;
    background:linear-gradient(180deg,#16202D 0%,#132232 100%);
    border-radius:10px;
    overflow:hidden;
    border:1px solid #2a3f5a;
}
.chat-header {
    padding:18px;
    background:rgba(30,67,97,.8);
    color:white;
    font-weight:bold;
    border-bottom:1px solid rgba(42,63,90,.5);
}
.messages {
    flex:1;
    padding:20px;
    overflow-y:auto;
}
.message {margin-bottom:18px;}
.message-user {font-weight:bold;color:#66C0F4;margin-right:10px;}
.message-time {color:#6b7b8f;font-size:.8rem;}
.message-content {
    margin-top:8px;
    color:#E6E6E6;
    line-height:1.5;
    padding-left:16px;
    border-left:2px solid rgba(102,192,244,.3);
}
.chat-input {
    display:flex;
    padding:15px;
    background:rgba(30,67,97,.8);
    border-top:1px solid rgba(42,63,90,.5);
}
.chat-input input {
    flex:1;
    background:rgba(22,32,45,.8);
    border:1px solid rgba(42,63,90,.5);
    padding:12px 18px;
    color:white;
    border-radius:8px;
    font-size:.95rem;
}
.chat-input button {
    background:linear-gradient(135deg,#66C0F4 0%,#4a9cd3 100%);
    color:#0B1A2A;
    border:none;
    padding:0 22px;
    margin-left:12px;
    border-radius:8px;
    font-weight:bold;
    cursor:pointer;
}
</style>

<div class="community-chat">
  <div class="channels">
    <div class="channel-group">
      <h3>GAME HELP</h3>
      <div class="channel active" data-channel="general-help">general-help</div>
    </div>
  </div>

  <div class="chat-container">
    <div class="chat-header">general-help</div>

    <div class="messages" id="messages">
      @foreach($messages as $m)
        <div class="message">
          <div>
            <span class="message-user">{{ $m->user->name }}</span>
            <span class="message-time">{{ $m->created_at->format('M d, H:i') }}</span>
          </div>
          <div class="message-content">{{ $m->content }}</div>
        </div>
      @endforeach
    </div>

    <div class="chat-input">
      <input type="text" id="messageInput" placeholder="Message #general-help">
      <button id="sendBtn">Send</button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
axios.defaults.headers.common['X-CSRF-TOKEN'] =
  document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const messagesDiv = document.getElementById('messages');
const input = document.getElementById('messageInput');
const sendBtn = document.getElementById('sendBtn');

function renderMessages(msgs) {
  messagesDiv.innerHTML = '';
  msgs.forEach(m=>{
    const div=document.createElement('div');
    div.className='message';
    div.innerHTML = `
      <div>
        <span class="message-user">${m.user.name}</span>
        <span class="message-time">${new Date(m.created_at).toLocaleTimeString()}</span>
      </div>
      <div class="message-content">${m.content}</div>`;
    messagesDiv.appendChild(div);
  });
  messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

function fetchMessages() {
  axios.get('/community/fetch')
    .then(res => renderMessages(res.data))
    .catch(console.error);
}

function sendMessage(){
  if(input.value.trim()==='') return;
  axios.post('/community/send',{content:input.value})
    .then(()=>{
      input.value='';
      fetchMessages();
    })
    .catch(console.error);
}

sendBtn.addEventListener('click',sendMessage);
input.addEventListener('keypress',e=>{if(e.key==='Enter')sendMessage();});

// Poll every 3 seconds
setInterval(fetchMessages,3000);
</script>
@endsection
