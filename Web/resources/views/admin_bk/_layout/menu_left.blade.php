<!-- Menu Start -->
<div class="col-auto d-none d-lg-flex">
    <ul class="sw-25 side-menu mb-0 primary" id="menuSide">
        @if (optional(auth()->user())->role == ADMIN_ROLE)
                <ul>
                    <li>
                        <a href="{{ route('cws.home') }}" class="active">
                            <i data-acorn-icon="navigate-diagonal" class="icon" data-acorn-size="18"></i>
                            <span class="label">{{ trans('nav.dashboard') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('cws.tasks') }}">
                            <i data-acorn-icon="form-check" class="icon d-none" data-acorn-size="18"></i>
                            <span class="label">Tasks</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('cws.events') }}">
                            <i data-acorn-icon="form-check" class="icon d-none" data-acorn-size="18"></i>
                            <span class="label">Events</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('cws.rewards') }}">
                            <i data-acorn-icon="form-check" class="icon d-none" data-acorn-size="18"></i>
                            <span class="label">Rewards</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('cws.groups') }}">
                            <i data-acorn-icon="form-check" class="icon d-none" data-acorn-size="18"></i>
                            <span class="label">Groups</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route(USER_LIST_ADMIN_ROUTER) }}">
                            <i data-acorn-icon="form-check" class="icon d-none" data-acorn-size="18"></i>
                            <span class="label">Users</span>
                        </a>
                    </li>
                </ul>
        @else
            <ul>
                <li>
                    <a href="{{ route('cws.home') }}" class="active">
                        <i data-acorn-icon="navigate-diagonal" class="icon" data-acorn-size="18"></i>
                        <span class="label">{{ trans('nav.dashboard') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('cws.events') }}">
                        <i data-acorn-icon="form-check" class="icon d-none" data-acorn-size="18"></i>
                        <span class="label">Events</span>
                    </a>
                </li>
            </ul>
        @endif
    </ul>
</div>
