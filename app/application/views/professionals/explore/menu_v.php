<ul id="nav_2" class="nav nav-pills nav-fill mb-4">
    <li class="nav-item" v-for="(menu, menu_key) in menu_elements">
        <a class="nav-link" href="#" v-on:click="set_tag(menu_key)" v-bind:class="{'active': menu.slug == tag.slug }">
            {{ menu.text }}
        </a>
    </li>
</ul>