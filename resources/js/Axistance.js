import axios from 'axios';

const Axistance = axios.create({
  baseURL: 'http://localhost:8081', // Replace with your Laravel API URL
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

export default Axistance;