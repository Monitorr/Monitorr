import express from 'express';

export const name = 'time';
export const router = express.Router();

router.get('/', (req, res) => {
    res.json({ date: Date.now() });
})

export default { name, router }