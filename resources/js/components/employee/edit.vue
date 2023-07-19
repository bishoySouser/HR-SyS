<template>
     <div ref="top" class="row">
        <div id="errors" class="col-md-8 bold-labels">

            <div class="alert alert-danger pb-0" v-if="errors &&  Object.keys(errors).length > 0">
                <ul class="list-unstyled">
                    
                    <li v-for="error in errors">
                        <i class="la la-info-circle"></i>
                        {{ error[0] }}
                    </li>
                    
                </ul>
            </div>

        <form @submit.prevent="submitForm('save_and_back')">

                <div class="card">
                    <div class="card-body row">
                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="full_name" bp-field-type="text">
                            <label>Full Name</label>
                            <input type="text" v-model="employee.full_name" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="email" bp-field-type="email">
                            <label>Email</label>
                            <input type="email" v-model="employee.email" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="phone_number" bp-field-type="tel">
                            <label>Phone Number</label>
                            <input type="tel" v-model="employee.phone_number" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="national_id" bp-field-type="text">
                            <label>National ID</label>
                            <input type="text" v-model="employee.national_id" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="birthday" bp-field-type="date">
                            <label>Birthday</label>
                            <input type="date" v-model="employee.birthday" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="location" bp-field-type="text">
                            <label>Location</label>
                            <input type="text" v-model="employee.location" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="gender" bp-field-type="select">
                            <label>Gender</label>
                            <select v-model="employee.gender" class="form-control">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="contract_period" bp-field-type="text">
                            <label>Contract Period</label>
                            <input type="text" v-model="employee.contract_period" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="hire_date" bp-field-type="date">
                            <label>Hire Date</label>
                            <input type="date" v-model="employee.hire_date" class="form-control" required>
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="top_management" bp-field-type="select">
                            <label>Top management</label>
                            <select v-model="employee.top_management" class="form-control">
                                <option value="ceo">ceo</option>
                                <option value="operation director">operation director</option>
                                <option value="manager">manager</option>
                                <option value="employee">employee</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="grades" bp-field-type="select">
                            <label>Grades</label>
                            <select v-model="employee.grades" class="form-control">
                                <option value="junior">junior</option>
                                <option value="associate">associate</option>
                                <option value="senior">senior</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="job_id" bp-field-type="select">
                            <label>Jobs</label>
                            <select v-model="employee.job_id" class="form-control">
                                <option value="" disabled>-</option>
                                <option v-for="item in jobs" :value="item.id">{{ item.title }}</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="salary" bp-field-type="number">
                            <label>Salary</label>
                            <input type="number" v-model="employee.salary" class="form-control" min="1000">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="departments" bp-field-type="select">
                            <label>Departments</label>
                            <select v-model="employee.department_id" class="form-control">
                                <option value="" disabled>-</option>
                                <option v-for="item in departments" :value="item.id">{{ item.name }}</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="manager_id" bp-field-type="select">
                            <label>Manager</label>
                            <select v-model="employee.manager_id" class="form-control">
                                <option value="">-</option>
                                <option v-for="item in managers" :value="item.id">{{ item.full_name }}</option>
                               
                            </select>
                        </div>

                        <div id="saveActions" class="form-group">

                            <input type="hidden" name="_save_action" value="save_and_back">
                                        <div class="btn-group" role="group">
                            
                            <button type="submit" class="btn btn-success">
                                <span class="la la-save" role="presentation" aria-hidden="true"></span> &nbsp;
                                <span data-value="save_and_back">Save and back</span>
                            </button>
                    
                            <div class="btn-group" role="group">
                                <button id="bpSaveButtonsGroup" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span><span class="sr-only">▼</span></button>
                                <div class="dropdown-menu" aria-labelledby="bpSaveButtonsGroup">
                                    <button type="button" class="dropdown-item" @click="submitForm('save_and_edit')">Save and edit this item</button>
                                    <button type="button" class="dropdown-item" @click="submitForm('save_and_new')">Save and new item</button>
                                    <button type="button" class="dropdown-item" @click="submitForm('save_and_preview')">Save and preview</button>
                                </div>
                            </div>
                        </div>
                        <a :href="urls.previous"  class="btn btn-default"><span class="la la-ban"></span> &nbsp;Cancel</a>
                        
                    </div>      
                </div>
            </div>
        </form>
        </div>
        </div>
    
  </template>
  
  <script>
  export default {
    name: 'EmployeeCreate',
    props: {
        employee: {
            type:Object
        },
        jobs: {
            type:Array
        },
        departments: {
            type:Array
        },
        managers: {
            type:Array
        },
        urls: {
            type:Object
        }
    },
    data() {
      return {
        errors: {}
      }
    },
    methods: {
        submitForm(submitFeedback) {
            this.errors = {}

            this.employee._save_action = submitFeedback;
            this.employee._http_referrer = this.urls.previous;

            axios.put('/admin/employee/' + this.employee.id, this.employee)
                .then(response => {
                    // Handle the success response for update
                    console.log(response.data);
                    window.location.href = response.data.redirect_url;
                })
                .catch(error => {
                    // Handle the error
                    if (error.code === 'ERR_BAD_REQUEST' && error.response.status === 422) {
                        this.errors = error.response.data.errors;
                        const element = this.$refs.errors;
                        
                        this.scrollToElement();
                    }
                    console.error(error);
                });
        },
        scrollToElement() {
            const element = this.$refs.top;
            if (element) {
            element.scrollIntoView({ behavior: 'smooth' });
        }
    }
}

  };
  </script>
  
  <style scoped>
  h1 {
    color: blue;
  }
  </style>