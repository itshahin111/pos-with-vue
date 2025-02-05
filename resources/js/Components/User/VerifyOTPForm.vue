<template>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6 center-screen">
                <div class="card animated fadeIn w-90  p-4">
                    <form @submit.prevent="submit">
                    <div class="card-body">
                        <h4>ENTER OTP CODE</h4>
                        <br/>
                        <label>4 Digit Code Here</label>
                        <input id="otp" v-model="form.otp" placeholder="Code" class="form-control" type="text"/>
                        <br/>
                        <button type="submit"  class="btn w-100 btn-success">Next</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>



<script setup>
import {useForm,router} from '@inertiajs/vue3'
const form = useForm({otp:''})
import { usePage } from '@inertiajs/vue3';
const page = usePage()
function submit(){
    if(form.otp.length===0){
        alert("OTP Required")
    }
    else {
        form.post("/verify-otp",{
            onSuccess:()=>{
                if(page.props.flash.status===true){
                    router.get("/ResetPasswordPage")
                }
                else {
                    alert(page.props.flash.message)
                }
            }
        })
    }
}
</script>

