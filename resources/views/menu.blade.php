@if(strstr(Auth::user()->email, 'optimum7.com'))
  <li>
    <a href="{{ route('webhook-process.index') }}">
      <div class="parent-icon icon-color-6"><i class="fas fa-list"></i>
      </div>
      <div class="menu-title">Webhook Process</div>
    </a>
  </li>
  <li>
    <a href="{{ route('webhook-process-errors.index') }}">
      <div class="parent-icon icon-color-7"><i class="fas fa-list-alt"></i>
      </div>
      <div class="menu-title">Webhook Errors</div>
    </a>
  </li>
@endif