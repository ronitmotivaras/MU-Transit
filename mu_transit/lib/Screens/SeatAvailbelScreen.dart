import 'package:flutter/material.dart';

class SeatAvailabelScreen extends StatefulWidget {
  const SeatAvailabelScreen({super.key});

  @override
  State<SeatAvailabelScreen> createState() => _SeatAvailabelScreenState();
}

class _SeatAvailabelScreenState extends State<SeatAvailabelScreen> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: const Center(
        child: Text(
          "Seat Availability Info",
          style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
        ),
      ),
    );
  }
}
