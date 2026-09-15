export const accounts = [
  { id: 'acc-carteira', name: 'Main wallet', balance: 478020, active: true },
  { id: 'acc-reserva', name: 'Savings', balance: 800000, active: true },
  { id: 'acc-viagem', name: 'Travel', balance: 125000, active: true },
];

export const transactions = [
  { id: 'tx-1', description: 'Salary', category: 'Income', date: 'Today, 09:42', amount: 520000, type: 'INFLOW', status: 'DONE' },
  { id: 'tx-2', description: 'Groceries', category: 'Food & dining', date: 'Yesterday, 18:20', amount: 18490, type: 'OUTFLOW', status: 'DONE' },
  { id: 'tx-3', description: 'Transfer to Lucas', category: 'Transfers', date: 'Sep 12, 14:10', amount: 8500, type: 'OUTFLOW', status: 'PENDING' },
  { id: 'tx-4', description: 'Music subscription', category: 'Entertainment', date: 'Sep 10, 08:00', amount: 2190, type: 'OUTFLOW', status: 'DONE' },
  { id: 'tx-5', description: 'Refund', category: 'Income', date: 'Sep 8, 11:35', amount: 7600, type: 'INFLOW', status: 'CANCELLED' },
];

export const money = (value: number) =>
  new Intl.NumberFormat('en-US', { style: 'currency', currency: 'BRL' }).format(value / 100);
