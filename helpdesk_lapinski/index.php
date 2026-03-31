<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helpdesk - Lapinski</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
<div id="mainApp" class="hidden h-full w-full flex flex-col overflow-hidden">
    <nav>
      <div class="max-w-7xl mx-auto flex items-center justify-between h-14">
        <div class="flex items-center gap-3">
          <img src=""  class="w-8 h-8 object-contain">
          <span id="navTitle" class="text-sm font-semibold text-bone tracking-wide hidden sm:block"></span>
        </div>
        <div class="hidden md:flex items-center gap-1">
          <button onclick="navigate('accueil')" data-nav="accueil" class="acceuil">Accueil</button>
          <button onclick="navigate('tickets')" data-nav="tickets" class="tickets">Tickets</button>


        </div>
        <div class="flex items-center gap-2">
          <button onclick="navigate('profil')" class="flex items-center gap-2 px-3 py-1.5 rounded border border-border hover:border-gold/30 transition-all">
            <img id="navAvatar" src="" alt="" class="w-5 h-5 rounded-full hidden object-cover">
            <i id="navAvatarIcon" data-lucide="user" class="w-3.5 h-3.5 text-gold"></i>
            <span id="navUser" class="text-xs text-cream/60 hidden sm:block">User</span>
          </button>
          <button id="mobileMenuBtn" onclick="toggleMobile()" class="md:hidden p-2 text-cream/60 hover:text-bone">
            <i data-lucide="menu" class="w-5 h-5"></i>
          </button>
        </div>
      </div>
    </nav>
</div>

</body>
</html>