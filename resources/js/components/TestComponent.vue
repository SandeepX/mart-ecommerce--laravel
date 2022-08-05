<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        <label for="jack">email</label>
                        <input type="text" id="jack" value="Jack" v-model="form['user_email']">
                        <br>
                        <label for="john">password</label>
                        <input type="text" id="john" value="John" v-model="form['user_password']">
                        <br>
                        <button @click="formSubmit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
export default {
    name:'TestComponent',
    mounted() {
        console.log('Component mounted.')
    },
    data(){
        return  {
            form: {
                user_email:'',
                user_passsword:''
                // formName: "Tell Us About Yourself",
                // userName: "",
                // favoriteColor: "Red",
                // favoriteHamburger: "",
                // favoriteHangout: [],
                // workHours: 0
            },
            showSubmitFeedback: false
        }
    },
    created() {
        axios.get(`http://localhost:8000/api/warehouse/warehouse-purchase-orders/create`).then(res => {
            console.log("responses", res)
        }).catch(err => {
            console.log("checking error", err)
        })
    },
    methods: {
        formSubmit(){
            const payload = {
                user_email: this.form['user_email'],
                user_password:this.form['user_password']
            }
            axios.post(`http://localhost:8001/test-vue`,payload).then(res => {
                console.log("responses", res)
            }).catch(err => {
                console.log("checking error", err)
            })
        }
    }
}
</script>
