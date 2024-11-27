<div class="w-36">
    <div class="sticky top-0 h-96 bg-green-400">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link @if($active === 'home' || $active === null) active @endif" aria-current="page" href="#">Logo</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if($active === 'donations') active @endif" href="#">My donations</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if($active === 'reports') active @endif" href="#">Resources</a>
            </li>
            <li class="nav-item nav-bottom">
                <a class="nav-link @if($active === 'profile') active @endif" href="#">Profile</a>
            </li>
        </ul>
    </div>
</div>