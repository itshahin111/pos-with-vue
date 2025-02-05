
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6 center-screen">
                <div class="card animated fadeIn w-90 p-4">
                    <form @submit.prevent="submit">
                    <div class="card-body">
                        <h4>SET NEW PASSWORD</h4>
                        <br/>
                        <label>New Password</label>
                        <input id="password" v-model="form.password" placeholder="New Password" class="form-control" type="password"/>
                        <br/>
                        <label>Confirm Password</label>
                        <input id="cpassword" v-model="form.cpassword" placeholder="Confirm Password" class="form-control" type="password"/>
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
const form = useForm({password:'',cpassword:''})
import { usePage } from '@inertiajs/vue3';
const page = usePage()
function submit(){
    if(form.password.length===0){
        alert("password Required")
    }
    else if(form.cpassword.length===0){
        alert("Confirm password Required")
    }
    else if(form.password!==form.cpassword){
        alert("Confirm password Invalid")
    }
    else {
        form.post("/reset-password",{
            onSuccess:()=>{
                if(page.props.flash.status===true){
                    router.get("/LoginPage")
                }
                else {
                    alert(page.props.flash.message)
                }
            }
        })
    }
}
</script>
