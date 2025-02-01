import 'package:flutter/material.dart';

class BusLocationScrenn extends StatefulWidget {
  const BusLocationScrenn({super.key});

  @override
  State<BusLocationScrenn> createState() => _BusLocationScrennState();
}

class _BusLocationScrennState extends State<BusLocationScrenn> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: const Center(
        child: Text(
          "Bus location will be displayed here",
          style: TextStyle(fontSize: 20),
        ),
      ),
    );
  }
}
