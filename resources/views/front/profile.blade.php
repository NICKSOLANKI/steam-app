<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Steam Profile Clone</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    *{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',sans-serif;}
    body{
      background:url("{{ asset('asset/imagies/ccm.png') }}") center/cover no-repeat fixed;
      color:#c7d5e0;min-height:100vh;
    }
    .backstab{
      position:fixed;top:15px;left:20px;
      background:#67c1f5;color:#fff;border:none;
      padding:8px 14px;border-radius:5px;
      cursor:pointer;z-index:1000;
    }
    .container{
      display:flex;max-width:1100px;margin:60px auto;
      border-radius:8px;overflow:hidden;
      box-shadow:0 0 20px rgba(0,0,0,.6);
    }
    .profile-left{
      flex-basis:75%;position:relative;
      background:url("{{ Auth::user()->profile_bg ? asset(Auth::user()->profile_bg) : asset('asset/imagies/cm.png') }}") center top/cover no-repeat;
      display:flex;flex-direction:column;
      justify-content:flex-start;padding:20px;min-height:1200px;
    }
    .profile-left::before{
      content:"";position:absolute;inset:0;
      background:rgba(0,0,0,.45);z-index:0;
    }
    .avatar{
      position:relative;z-index:1;align-self:flex-start;
      display:flex;align-items:center;gap:12px;
      background:rgba(0,0,0,.6);padding:10px 15px;
      border-radius:8px;margin-top:50px;
    }
    .avatar img{
      width:110px;height:110px;object-fit:cover;
      border-radius:6px;border:2px solid #67c1f5;
    }
    .mini-profile{
      margin-top:10px; display:flex; align-items:center; gap:10px;
    }
    .mini-profile img{
      width:60px;height:60px;object-fit:cover;
      border-radius:4px;border:2px solid #67c1f5;
    }
    .avatar-info h2{font-size:22px;color:#fff;}
    .edit-btn{
      margin-top:10px;background:#4b6c8c;
      color:#fff;padding:6px 12px;border:none;
      border-radius:4px;cursor:pointer;
    }
    .edit-form{
      display:none;margin-top:10px;z-index:2;
    }
    .edit-form input{
      padding:6px;width:220px;border-radius:4px;
      border:1px solid #555;background:#1b2838;color:#fff;
    }
    .edit-form button{
      padding:6px 12px;margin-left:6px;
      background:#67c1f5;color:#fff;border:none;
      border-radius:4px;cursor:pointer;
    }
    .profile-right{
      width:280px;background:rgba(27,40,56,.9);
      padding:20px;border-left:1px solid #3d4450;
      max-height:1200px;overflow-y:auto;
    }
    .level{font-weight:bold;padding:8px 12px;
      background:linear-gradient(90deg,#4b6c8c,#6b98be);
      border-radius:5px;display:inline-block;
      margin-bottom:20px;color:#fff;
      box-shadow:0 0 10px rgba(107,152,190,.3);}
    .section{margin-bottom:20px;}
    .section h3{
      font-size:15px;color:#67c1f5;margin-bottom:10px;
      border-bottom:1px solid #3d4450;padding-bottom:6px;
    }
    .stats p{font-size:14px;margin:5px 0;display:flex;align-items:center;gap:6px;}
    .badges{display:flex;gap:6px;flex-wrap:wrap;margin-top:6px;}
    .badges img{width:36px;height:36px;border-radius:4px;border:1px solid #444;}
    .profile-right::-webkit-scrollbar{width:8px;}
    .profile-right::-webkit-scrollbar-track{background:rgba(27,40,56,.5);}
    .profile-right::-webkit-scrollbar-thumb{background:#67c1f5;border-radius:4px;}
  </style>
</head>
<body>
  <button class="backstab" onclick="window.location.href='{{ route('notlogin.index') }}'">
    <i class="fa fa-arrow-left"></i> Back
  </button>

  <div class="container">
    <!-- Left Side -->
    <div class="profile-left" id="profileLeft">
      <div class="avatar">
        <img id="avatarImg"
             src="{{ Auth::user()->avatar ?: asset('asset/imagies/default_avatar.png') }}"
             alt="Avatar">

        <div class="avatar-info">
          <h2 id="username">{{ Auth::user()->name }}</h2>

          <!-- Edit Username -->
          <button class="edit-btn" onclick="toggleEdit()">Edit Username</button>
          <div class="edit-form" id="editForm">
            <input type="text" id="newUsername" placeholder="Enter new username" value="{{ Auth::user()->name }}" novalidate>
            <button onclick="saveUsername()">Save</button>
          </div>

          <!-- Edit Avatar -->
          <button class="edit-btn" onclick="toggleAvatarEdit()">Edit Avatar</button>
          <div class="edit-form" id="avatarEditForm">
            <input type="file" id="newAvatar" accept="image/*">
            <button onclick="saveAvatar()">Upload</button>
          </div>

          <!-- Edit Background -->
          <button class="edit-btn" onclick="toggleBgEdit()">Edit Background</button>
          <div class="edit-form" id="bgEditForm">
            <input type="file" id="newBgFile" accept="image/*">
            <button onclick="saveBackground()">Upload</button>
          </div>  

          <!-- Mini Profile -->
          <div class="mini-profile">
            <img id="miniProfileImg"
                 src="{{ Auth::user()->mini_profile ? Auth::user()->mini_profile : asset('asset/imagies/smp.webp') }}"
                 alt="Mini Profile">

            <button class="edit-btn" onclick="toggleMiniProfileEdit()">Edit Mini Profile</button>
            <div class="edit-form" id="miniProfileEditForm">
              <input type="file" id="newMiniProfile" accept="image/*">
              <button onclick="saveMiniProfile()">Upload</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Sidebar -->
    <div class="profile-right">
      <div class="level">Level 120</div>

      <div class="section">
        <h3>Currently Online</h3>
        <div class="badges">
          <img src="{{ asset('asset/imagies/1.jpg') }}">
          <img src="{{ asset('asset/imagies/2.jpg') }}">
          <img src="{{ asset('asset/imagies/3.jpg') }}">
        </div>
      </div>

      <div class="section stats">
        <h3>Stats</h3>
        <p><i class="fa fa-gamepad"></i> Games: 57</p>
        <p><i class="fa fa-image"></i> Screenshots: 16</p>
        <p><i class="fa fa-star"></i> Reviews: 1</p>
        <p><i class="fa fa-users"></i> Groups: 19</p>
        <p><i class="fa fa-user-friends"></i> Friends: 116</p>
        <p><i class="fa fa-trophy"></i> Achievements: 134</p>
        <p><i class="fa fa-certificate"></i> Badges Earned: 12</p>
        <p><i class="fa fa-heart"></i> Wishlisted Games: 25</p>
      </div>

      <div class="section">
        <h3>Community</h3>
        <p><i class="fa fa-users"></i> Friends Online: 8</p>
        <p><i class="fa fa-gamepad"></i> Games in Common: 23</p>
        <p><i class="fa fa-image"></i> Screenshots Uploaded: 16</p>
        <p><i class="fa fa-star"></i> Reviews Written: 1</p>
        <p><i class="fa fa-users-cog"></i> Groups Joined: 19</p>
      </div>

      <div class="section">
        <h3>Recent Activity</h3>
        <p>Played Cyberpunk 2077 for 5 hours</p>
        <p>Unlocked "Night City Explorer" Achievement</p>
        <p>Reviewed "Valheim"</p>
        <p>Uploaded 3 new screenshots</p>
      </div>

      <div class="section">
        <h3>Groups</h3>
        <div class="badges">
          <img src="{{ asset('asset/imagies/a4.jpg') }}">
          <img src="{{ asset('asset/imagies/mp.webp') }}">
          <img src="{{ asset('asset/imagies/sea.jpg') }}">
          <img src="{{ asset('asset/imagies/val.webp') }}">
          <img src="{{ asset('asset/imagies/ws.webp') }}">
          <img src="{{ asset('asset/imagies/a4.jpg') }}">
          <img src="{{ asset('asset/imagies/a4.jpg') }}">
          <img src="{{ asset('asset/imagies/a4.jpg') }}">
          <img src="{{ asset('asset/imagies/a4.jpg') }}">
          <img src="{{ asset('asset/imagies/a4.jpg') }}">
          <img src="{{ asset('asset/imagies/a4.jpg') }}">
          <img src="{{ asset('asset/imagies/a4.jpg') }}">
          <img src="{{ asset('asset/imagies/a4.jpg') }}">
        </div>
      </div>
    </div>
  </div>

  <script>
    function toggleEdit(){ toggleDisplay('editForm'); }
    function toggleAvatarEdit(){ toggleDisplay('avatarEditForm'); }
    function toggleBgEdit(){ toggleDisplay('bgEditForm'); }
    function toggleMiniProfileEdit(){ toggleDisplay('miniProfileEditForm'); }
    function toggleDisplay(id){ const f=document.getElementById(id); f.style.display=f.style.display==='block'?'none':'block'; }

    async function safeJson(res){
      const text = await res.text();
      try {
        return JSON.parse(text);
      } catch(e) {
        return {
          success:false,
          message: res.status===422 ? "Validation failed: Please upload a valid image (jpg, png, jpeg)." : "Invalid response",
          raw:text
        };
      }
    }

    function saveUsername(){
      const username=document.getElementById('newUsername').value.trim();
      if(!username) return alert('Please enter a username');
      fetch("{{ route('profile.updateUsername') }}",{
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
        body:JSON.stringify({name: username})
      }).then(safeJson).then(data=>{
        if(data.success){
          document.getElementById('username').innerText=username;
          toggleEdit();
        } else alert(data.message || 'Update failed!');
      }).catch(err=>console.error(err));
    }

    function saveAvatar(){
      const file=document.getElementById('newAvatar').files[0];
      if(!file) return alert('Please select a file');
      const formData=new FormData();
      formData.append('avatar', file);
      fetch("{{ route('profile.updateAvatar') }}",{
        method:'POST',
        headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
        body:formData
      }).then(safeJson).then(data=>{
        if(data.success){
          document.getElementById('avatarImg').src=data.avatar_url;
          toggleAvatarEdit();
        } else alert(data.message || 'Upload failed!');
      }).catch(err=>console.error(err));
    }

    function saveBackground(){
      const file=document.getElementById('newBgFile').files[0];
      if(!file) return alert('Please select a file');
      const formData=new FormData();
      formData.append('background', file);
      fetch("{{ route('profile.updateBackground') }}",{
        method:'POST',
        headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
        body:formData
      }).then(safeJson).then(data=>{
        if(data.success){
          document.getElementById('profileLeft').style.backgroundImage=`url('${data.bg_url}')`;
          toggleBgEdit();
        } else alert(data.message || 'Upload failed!');
      }).catch(err=>console.error(err));
    }

    function saveMiniProfile(){
      const file=document.getElementById('newMiniProfile').files[0];
      if(!file) return alert('Please select a file');
      const formData=new FormData();
      formData.append('mini_profile', file);
      fetch("{{ route('profile.updateMiniProfile') }}",{
        method:'POST',
        headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
        body:formData
      }).then(safeJson).then(data=>{
        if(data.success){
          document.getElementById('miniProfileImg').src=data.mini_profile_url;
          toggleMiniProfileEdit();
        } else alert(data.message || 'Upload failed!');
      }).catch(err=>console.error(err));
    }
  </script>
</body>
</html>
