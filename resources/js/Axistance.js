import axios from 'axios';

const Axistance = axios.create({
  // baseURL: 'https://almakkahyarnthread.com', // Replace with your Laravel API URL
  baseURL: 'http://localhost/amyt/public/admin', // Replace with your Laravel API URL
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

export default Axistance;