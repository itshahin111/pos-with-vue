<template>
    <Head title="Login" />
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6 center-screen">
                <div class="card shadow-lg p-4">
                    <form @submit.prevent="submitForm" novalidate>
                        <div class="card-header text-center bg-info text-white">
                            <h4>Login Form</h4>
                        </div>

                        <div class="card-body">
                            <!-- Email Field -->
                            <div class="form-group mb-3">
                                <label for="email">Email address</label>
                                <input
                                    type="email"
                                    id="email"
                                    v-model="form.email"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.email }"
                                    placeholder="Enter email"
                                    autocomplete="email"
                                />
                                <div v-if="form.errors.email" class="invalid-feedback">
                                    {{ form.errors.email }}
                                </div>
                            </div>

                            <!-- Password Field -->
                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input
                                    type="password"
                                    id="password"
                                    v-model="form.password"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.password }"
                                    placeholder="Enter password"
                                    autocomplete="current-password"
                                />
                                <div v-if="form.errors.password" class="invalid-feedback">
                                    {{ form.errors.password }}
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            <button type="submit" class="btn btn-success w-100" :disabled="form.processing">
                                <span v-if="form.processing" class="spinner-border spinner-border-sm"></span>
                                {{ form.processing ? 'Logging in...' : 'Login' }}
                            </button>
                            <div class="mt-2">
                                <Link href="/register" class="btn btn-link">Register</Link>
                                <span>|</span>
                                <Link href="/forgot-password" class="btn btn-link">Forgot Password</Link>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, useForm, Link, router } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";

const toast = useToast();

const form = useForm({
    email: '',
    password: '',
});

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function submitForm() {
    form.clearErrors();

    // Client-side validation (XSS/Empty Input)
    let hasError = false;

    if (!form.email.trim()) {
        form.setError("email", "Email is required");
        toast.error("Email is required");
        hasError = true;
    } else if (!validateEmail(form.email)) {
        form.setError("email", "Email format is invalid");
        toast.error("Email format is invalid");
        hasError = true;
    }

    if (!form.password.trim()) {
        form.setError("password", "Password is required");
        toast.error("Password is required");
        hasError = true;
    }

    if (hasError) return;

    // ✅ Server request via Inertia (safe from XSS/CSRF)
    form.post('/user-login', {
        onSuccess: () => {
            toast.success("Login successful!");
            router.get('/dashboard');
        },
        onError: () => {
            toast.error("Login failed. Please check credentials.");
        }
    });
}
</script>

<style scoped>
.center-screen {
    margin-top: 5vh;
    margin-bottom: 5vh;
}
.invalid-feedback {
    display: block;
}
.btn:disabled {
    opacity: 0.7;
}
</style>
