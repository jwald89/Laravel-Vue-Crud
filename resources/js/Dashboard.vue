<template>
    <div class="container p-2">
        <h1 class="text-primary">DASHBOARD</h1>
        <div class="card p-2">
            <nav class="navbar navbar-expand-sm bg-light">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <router-link class="nav-link" :to="'/'">
                            Home
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" :to="'/personal-details'">
                            List
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            class="nav-link"
                            :to="'/create-personal-details'"
                        >
                            Create
                        </router-link>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>
