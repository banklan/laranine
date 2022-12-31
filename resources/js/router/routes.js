// import Vue from 'vue'
// import VueRouter from 'vue-router'

// Vue.use(VueRouter)

import Welcome from '../components/Welcome.vue'
import Register from '../components/Register'
import Login from '../components/Login'
import UserEmailConfirmation from '../components/UserEmailConfirmation'
import UserEmailVerificationRequired from '../components/UserEmailVerificationRequired'
import UserDashboard from '../components/user/Dashboard'
import CreateService from '../components/user/CreateService'
import CreateServiceUploadImage from '../components/user/CreateServiceUploadImage'
import MyService from '../components/user/MyService'
import UpdateServiceImage from '../components/user/UpdateServiceImage'
import CreatePortfolio from '../components/user/CreatePortfolio'
import CreatePfUploadPics from '../components/user/CreatePfUploadPics'
import MyPortfolioDetail from '../components/user/MyPortfolioDetail'
import EditMyPortfolio from '../components/user/EditMyPortfolio'
import UpdatePortfolioImages from '../components/user/UpdatePortfolioImages'
// import MyProfile from '../components/user/MyProfile'
import EditMyService from '../components/user/EditMyService'
import UserProfile from '../components/user/Profile'
import UpdateAuthUser from '../components/user/UpdateAuthUser'
import MyServiceReviews from '../components/user/UpdateAuthUser'
import AdminLogin from '../components/admin/AdminLogin'
import AdminDashboard from '../components/admin/AdminDashboard'
import AdminUsersList from '../components/admin/AdminUsersList'
import AdminUserDetail from '../components/admin/AdminUserDetail'
import AdminUpdateUser from '../components/admin/AdminUpdateUser'
import AdminUserSearchResult from '../components/admin/AdminUserSearchResult'
import AdminSuperUsersList from '../components/admin/AdminSuperUsersList'
import AdminSuperUserDetail from '../components/admin/AdminSuperUserDetail'
import AdminSuperUserUpdateProfPic from '../components/admin/AdminSuperUserUpdateProfPic'
import AdminSuperUserUpdate from '../components/admin/AdminSuperUserUpdate'
import AdminAdminSearchResult from '../components/admin/AdminAdminSearchResult'
import AdminServicesList from '../components/admin/AdminServicesList'
import AdminServiceDetail from '../components/admin/AdminServiceDetail'
import AdminUpdateService from '../components/admin/AdminUpdateService'
import AdminPortfolioList from '../components/admin/AdminPortfolioList'
import AdminPortfolioDetail from '../components/admin/AdminPortfolioDetail'
import AdminUpdatePortfolio from '../components/admin/AdminUpdatePortfolio'
import AdminReviewList from '../components/admin/AdminReviewList'
import Test from '../components/Test'

export default [
    // { path: '*', name: 'NotFound', component: NotFound },
    { path: '/', name: 'Welcome', component: Welcome },
    { path: '/login', name: 'Login', component: Login },
    { path: '/register', name: 'Register', component: Register },
    { path: '/test', name: 'Test', component: Test },
    { path: '/email-confirmation', name: 'UserEmailConfirmation', component: UserEmailConfirmation, props: true },
    { path: '/email-verification-required', name: 'UserEmailVerificationRequired', component: UserEmailVerificationRequired, props: true },
    {
        path: '/user/dashboard', name: 'UserDashboard', component: UserDashboard,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/user/create-service/add-image', name: 'CreateServiceUploadImage', component: CreateServiceUploadImage,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/user/create-service', name: 'CreateService', component: CreateService,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/user/my-service/update-file', name: 'UpdateServiceImage', component: UpdateServiceImage,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/user/my-service/update', name: 'EditMyService', component: EditMyService,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/user/my-service', name: 'MyService', component: MyService,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/user/reviews', name: 'MyServiceReviews', component: MyServiceReviews,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/user/create-portfolio/:id/upload', name: 'CreatePfUploadPics', component: CreatePfUploadPics,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/user/create-portfolio', name: 'CreatePortfolio', component: CreatePortfolio,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/user/portfolio/:id/update-files', name: 'UpdatePortfolioImages', component: UpdatePortfolioImages,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/user/portfolio/:id/update', name: 'EditMyPortfolio', component: EditMyPortfolio,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/user/portfolio/:id', name: 'MyPortfolioDetail', component: MyPortfolioDetail,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/user/account-update', name: 'UpdateAuthUser', component: UpdateAuthUser,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/user/profile', name: 'UserProfile', component: UserProfile,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/admin-login', name: 'AdminLogin', component: AdminLogin,
    },
    {
        path: '/admin', name: 'AdminDashboard', component: AdminDashboard,
        meta: {
            requiresAdminAuth: true
        },
    },
    {
        path: '/admin/users', name: 'AdminUsersList', component: AdminUsersList,
        meta: {
            requiresAdminAuth: true
        },
    },
    {
        path: '/admin/users/:id/update', name: 'AdminUpdateUser', component: AdminUpdateUser,
        meta: {
            requiresAdminAuth: true
        },
    },
    {
        path: '/admin/users/:id', name: 'AdminUserDetail', component: AdminUserDetail,
        meta: {
            requiresAdminAuth: true
        },
    },
    {
        path: '/admin/users/search', name: 'AdminUserSearchResult', component: AdminUserSearchResult, props: true,
        meta: {
            requiresAdminAuth: true
        },
    },
    {
        path: '/admin/superusers/:id/update-profile-picture', name: 'AdminSuperUserUpdateProfPic', component: AdminSuperUserUpdateProfPic,
        meta: {
            requiresAdminAuth: true
        },
    },
    {
        path: '/admin/superusers/:id/update', name: 'AdminSuperUserUpdate', component: AdminSuperUserUpdate,
        meta: {
            requiresAdminAuth: true
        },
    },
    {
        path: '/admin/superusers/:id', name: 'AdminSuperUserDetail', component: AdminSuperUserDetail,
        meta: {
            requiresAdminAuth: true
        },
    },

    {
        path: '/admin/superusers', name: 'AdminSuperUsersList', component: AdminSuperUsersList,
        meta: {
            requiresAdminAuth: true
        },
    },
    {
        path: '/admin/superusers', name: 'AdminAdminSearchResult', component: AdminAdminSearchResult, props: true,
        meta: {
            requiresAdminAuth: true
        },
    },
    {
        path: '/admin/services/:id/update', name: 'AdminUpdateService', component: AdminUpdateService,
        meta: {
            requiresAdminAuth: true
        },
    },
    {
        path: '/admin/services/:id', name: 'AdminServiceDetail', component: AdminServiceDetail,
        meta: {
            requiresAdminAuth: true
        },
    },
    {
        path: '/admin/services', name: 'AdminServicesList', component: AdminServicesList,
        meta: {
            requiresAdminAuth: true
        },
    },
    {
        path: '/admin/portfolio/:id/update', name: 'AdminUpdatePortfolio', component: AdminUpdatePortfolio,
        meta: {
            requiresAdminAuth: true
        },
    },
    {
        path: '/admin/portfolio/:id', name: 'AdminPortfolioDetail', component: AdminPortfolioDetail,
        meta: {
            requiresAdminAuth: true
        },
    },
    {
        path: '/admin/portfolios', name: 'AdminPortfolioList', component: AdminPortfolioList,
        meta: {
            requiresAdminAuth: true
        },
    },
    {
        path: '/admin/reviews', name: 'AdminReviewList', component: AdminReviewList,
        meta: {
            requiresAdminAuth: true
        },
    },
];
