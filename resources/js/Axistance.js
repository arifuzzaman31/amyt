import axios from 'axios';

const Axistance = axios.create({
<<<<<<< HEAD
  baseURL: 'https://almakkahyarnthread.com', // Replace with your Laravel API URL
=======
  baseURL: 'http://localhost/amyt/public/admin', // Replace with your Laravel API URL
>>>>>>> 57dff2014db996826db704ef01f852e2227d81ba
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

export default Axistance;