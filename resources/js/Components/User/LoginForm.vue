<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6 center-screen">
                <div class="card shadow-lg p-4">
                    <form @submit.prevent="submitForm">
                        <div class="card-header text-center bg-info text-white">
                            <h4>Login Form</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="email" class="form-label"
                                    >Email address</label
                                >
                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    placeholder="Enter email"
                                    v-model="form.email"
                                />
                            </div>
                            <div class="form-group mb-3">
                                <label for="password" class="form-label"
                                    >Password</label
                                >
                                <input
                                    type="password"
                                    class="form-control"
                                    id="password"
                                    placeholder="Password"
                                    v-model="form.password"
                                />
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <button
                                type="submit"
                                class="btn btn-success w-100 mb-2"
                            >
                                Login
                            </button>
                            <div class="text-center">
                                <Link href="/register" class="btn btn-link">
                                    Sign Up
                                </Link>
                                <span class="ms-1">|</span>
                                <Link
                                    href="/forgot-password"
                                    class="btn btn-link"
                                >
                                    Forgot Password
                                </Link>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useToast } from "vue-toastification";
import { useForm, router, Link } from "@inertiajs/vue3";

const toast = useToast();

const form = useForm({
    email: "",
    password: "",
});

function submitForm() {
    if (!form.email.trim()) {
        toast.error("Email is required");
        return;
    }
    if (!form.password.trim()) {
        toast.error("Password is required");
        return;
    }

    form.post("/user-login", {
        onSuccess: () => {
            toast.success("Login successful!");
            router.get("/dashboard"); // Redirect to dashboard
        },
        onError: () => {
            toast.error("Login failed. Please check your credentials.");
        },
    });
}
</script>
