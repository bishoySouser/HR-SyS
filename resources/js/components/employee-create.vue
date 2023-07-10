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

        <form @submit.prevent="submitForm">
                
                <input type="hidden" name="_http_referrer" value="{{ url()->previous() }}">

                <div class="card">
                    <div class="card-body row">
                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="full_name" bp-field-type="text">
                            <label>Full Name</label>
                            <input type="text" v-model="formData.full_name" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="email" bp-field-type="email">
                            <label>Email</label>
                            <input type="email" v-model="formData.email" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="phone_number" bp-field-type="tel">
                            <label>Phone Number</label>
                            <input type="tel" v-model="formData.phone_number" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="national_id" bp-field-type="text">
                            <label>National ID</label>
                            <input type="text" v-model="formData.national_id" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="birthday" bp-field-type="date">
                            <label>Birthday</label>
                            <input type="date" v-model="formData.birthday" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="location" bp-field-type="text">
                            <label>Location</label>
                            <input type="text" v-model="formData.location" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="gender" bp-field-type="select">
                            <label>Gender</label>
                            <select v-model="formData.gender" class="form-control">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="contract_period" bp-field-type="text">
                            <label>Contract Period</label>
                            <input type="text" v-model="formData.contract_period" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="hire_date" bp-field-type="date">
                            <label>Hire Date</label>
                            <input type="date" v-model="formData.hire_date" class="form-control" required>
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="top_management" bp-field-type="select">
                            <label>Top management</label>
                            <select v-model="formData.top_management" class="form-control">
                                <option value="ceo">ceo</option>
                                <option value="operation director">operation director</option>
                                <option value="manager">manager</option>
                                <option value="employee">employee</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="grades" bp-field-type="select">
                            <label>Grades</label>
                            <select v-model="formData.grades" class="form-control">
                                <option value="junior">junior</option>
                                <option value="associate">associate</option>
                                <option value="senior">senior</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="job_id" bp-field-type="select">
                            <label>Jobs</label>
                            <select v-model="formData.job" class="form-control">
                                <option value="" disabled>-</option>
                                <option v-for="item in jobs" :value="item.id">{{ item.title }}</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="salary" bp-field-type="number">
                            <label>Salary</label>
                            <input type="number" v-model="formData.salary" class="form-control" min="1000">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="departments" bp-field-type="select">
                            <label>Departments</label>
                            <select v-model="formData.department_id" class="form-control">
                                <option value="" disabled>-</option>
                                <option v-for="item in departments" :value="item.id">{{ item.name }}</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="manager_id" bp-field-type="select">
                            <label>Manager</label>
                            <select v-model="formData.manager_id" class="form-control">
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
                                    <button type="button" class="dropdown-item" data-value="save_and_edit">Save and edit this item</button>
                                    <button type="button" class="dropdown-item" data-value="save_and_new">Save and new item</button>
                                    <button type="button" class="dropdown-item" data-value="save_and_preview">Save and preview</button>
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
        formData: {
            full_name: '',
            email: '',
            phone_number: '',
            national_id: '',
            birthday: null,
            location: '',
            gender: 'male',
            contract_period: '',
            hire_date: new Date().toISOString().slice(0, 10),
            grades: 'junior',
            top_management: 'employee',
            job_id: null,
            salary: null,
            manager_id: null,
            department_id: null,
            // Add other form fields here with default values if needed
        },
        errors: {}
      }
    },
    methods: {
        submitForm() {
            axios.post('/admin/employee', this.formData)
            .then(response => {
                // Handle the success response
                console.log(response.data);
            })
            .catch(error => {
                // Handle the error
                if (error.code = 'ERR_BAD_REQUEST' && error.response.status == 422) {
                    this.errors = error.response.data.errors
                    const element = this.$refs.errors;
                    
                    this.scrollToElement()
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