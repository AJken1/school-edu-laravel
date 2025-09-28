<!-- Sidebar -->
<div class="sidebar">
  <a href="{{ route('admin.dashboard') }}" class="logo" style="display:flex;flex-direction:column;justify-content:center;align-items:center;padding:30px 10px">
      <img src="{{ asset('images/whitemode.png') }}" alt="EDUgate" style="height:150px;width:auto;object-fit:contain;max-width:100%;margin-bottom:5px;">
  </a>
  <ul class="side-menu">
      <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <a href="{{ route('admin.dashboard') }}">
              <i class='bx bxs-dashboard'></i>Dashboard
          </a>
      </li>
      <li class="{{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
          <a href="{{ route('admin.students.index') }}">
              <i class='bx bxs-group'></i>Students
          </a>
      </li>
      <li class="{{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
          <a href="{{ route('admin.teachers.index') }}">
              <i class='bx bxs-user'></i>Teachers
          </a>
      </li>
      <li class="{{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
          <a href="{{ route('admin.subjects.index') }}">
              <i class='bx bxs-book'></i>Subjects
          </a>
      </li>
      <li class="{{ request()->routeIs('admin.files.*') ? 'active' : '' }}">
          <a href="{{ route('admin.files.index') }}">
              <i class='bx bxs-folder-open'></i>Files
          </a>
      </li>
      
  </ul>
</div>

<!-- Logout Form (Hidden) -->
<form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>


</script>