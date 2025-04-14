import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';

class LostandfoundScreen extends StatefulWidget {
  const LostandfoundScreen({super.key});

  @override
  State<LostandfoundScreen> createState() => _LostandfoundScreenState();
}

class _LostandfoundScreenState extends State<LostandfoundScreen> with SingleTickerProviderStateMixin {
  late TabController _tabController;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Lost & Found'),
        centerTitle: true,
        bottom: TabBar(
          controller: _tabController,
          tabs: const [
            Tab(text: 'Lost'),
            Tab(text: 'Found'),
          ],
          indicatorColor: Colors.blue, // Same color for the indicator
        ),
      ),
      body: TabBarView(
        controller: _tabController,
        children: [
          // Lost Section
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: Container(
              padding: const EdgeInsets.all(16.0),
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(10),
                color: Colors.grey[200],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text("Lost Item Details", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 10),
                  const Text("Item Name:"),
                  const TextField(),
                  const SizedBox(height: 10),
                  const Text("Date:"),
                  const TextField(),
                  const SizedBox(height: 10),
                  const Text("Shift Time:"),
                  const TextField(),
                  const SizedBox(height: 10),
                  const Text("Upload Image:"),
                  IconButton(
                    icon: const Icon(Icons.upload),
                    onPressed: () {
                      // Handle image upload logic
                    },
                  ),
                ],
              ),
            ),
          ),

          // Found Section
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: Container(
              padding: const EdgeInsets.all(16.0),
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(10),
                color: Colors.grey[200],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text("Found Item Details", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 10),
                  const Text("Item Name:"),
                  const TextField(),
                  const SizedBox(height: 10),
                  const Text("Date:"),
                  const TextField(),
                  const SizedBox(height: 10),
                  const Text("Shift Time:"),
                  const TextField(),
                  const SizedBox(height: 10),
                  const Text("Upload Image:"),
                  IconButton(
                    icon: const Icon(Icons.upload),
                    onPressed: () {
                      // Handle image upload logic
                    },
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}
