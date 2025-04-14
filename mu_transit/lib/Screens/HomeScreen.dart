import 'package:flutter/material.dart';
import 'package:mu_transit/Screens/profilScreen.dart';

import 'LostandFound.dart';
import 'locationScreen.dart';
import 'seatAvailabelScreen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _selectedIndex = 0;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[200],
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const SizedBox(height: 0),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Row(
                  children: [
                    Icon(Icons.directions_bus, color: Colors.teal, size: 28),
                    const SizedBox(width: 5),
                    const Text(
                      "MU Transit",
                      style: TextStyle(
                        fontSize: 22,
                        fontWeight: FontWeight.bold,
                        color: Colors.teal,
                      ),
                    ),
                  ],
                ),
                CircleAvatar(
                  backgroundColor: Colors.grey[300],
                  child: IconButton(
                    icon: const Icon(Icons.person, color: Colors.black),
                    onPressed: () {
                      // Navigate to the ProfileScreen when the profile icon is clicked
                      Navigator.push(
                        context,
                        MaterialPageRoute(builder: (context) => const Profilscreen()),
                      );
                    },
                  ),
                )

              ],
            ),
            const SizedBox(height: 20),
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(12),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black12,
                    blurRadius: 6,
                    spreadRadius: 2,
                  ),
                ],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      const Text("One-way", style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                      const SizedBox(width: 10),
                      Text("Round trip", style: TextStyle(fontSize: 16, color: Colors.grey[600])),
                      const Spacer(),
                      const Icon(Icons.person, color: Colors.black54),
                      const Text(" 2", style: TextStyle(fontSize: 16)),
                    ],
                  ),
                  const SizedBox(height: 16),
                  _buildTextField("Manchester, United Kingdom"),
                  const SizedBox(height: 10),
                  _buildTextField("Paris, France"),
                  const SizedBox(height: 10),
                  _buildTextField("Sun, Nov 6"),
                  const SizedBox(height: 16),
                  ElevatedButton(
                    onPressed: () {},
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.black,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(8),
                      ),
                    ),
                    child: const Center(
                      child: Padding(
                        padding: EdgeInsets.symmetric(vertical: 14),
                        child: Text("Search", style: TextStyle(fontSize: 18, color: Colors.white)),
                      ),
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),
            const Text("Cheap bus tickets", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 10),
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(12),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black12,
                    blurRadius: 6,
                    spreadRadius: 2,
                  ),
                ],
              ),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: const [
                      Text("Cheapest", style: TextStyle(fontSize: 16, color: Colors.grey)),
                      SizedBox(height: 5),
                      Text("£125", style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                    ],
                  ),
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: const [
                      Text("Average", style: TextStyle(fontSize: 16, color: Colors.grey)),
                      SizedBox(height: 5),
                      Text("£146", style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                    ],
                  ),
                  ElevatedButton(
                    onPressed: () {},
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.teal,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(8),
                      ),
                    ),
                    child: const Text("Find ticket", style: TextStyle(color: Colors.white)),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
      bottomNavigationBar: Container(
        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 5),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(30),
          boxShadow: [
            BoxShadow(
              color: Colors.black12,
              blurRadius: 10,
              spreadRadius: 2,
            ),
          ],
        ),
        margin: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceAround,
          children: [
            _bottomNavItem(Icons.home_rounded, "Home", 0),
          _bottomNavItem(Icons.location_on, "Bus Location", 1),
            _bottomNavItem(Icons.airline_seat_recline_normal_rounded, "Seats", 2),
            _bottomNavItem(Icons.search_rounded, "Lost & Found", 3),
          ],
        ),

      ),
    );
  }

  Widget _bottomNavItem(IconData icon, String label, int index) {
    return GestureDetector(
      onTap: () {
        setState(() {
          _selectedIndex = index;
        });

        // Check if the tapped index is for "Bus Location" (index 1 in your case)
        if (index == 1) {
          // Navigate to the LocationScreen when "Bus Location" is clicked
          Navigator.push(
            context,
            MaterialPageRoute(builder: (context) => const LocationScreen()),
          );
        }

        // Check if the tapped index is for "Seats" (index 2 in your case)
        if (index == 2) {
          // Navigate to the SeatAvailableScreen when "Seats" is clicked
          Navigator.push(
            context,
            MaterialPageRoute(builder: (context) => const SeatAvailabelScreen()),
          );
        }

        // Check if the tapped index is for "Lost & Found" (index 3 in your case)
        if (index == 3) {
          // Navigate to the LostAndFoundScreen when "Lost & Found" is clicked
          Navigator.push(
            context,
            MaterialPageRoute(builder: (context) => const LostandfoundScreen()),
          );
        }

        // You can add more conditions for other indices as needed
      },
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, color: _selectedIndex == index ? Colors.black : Colors.grey),
          Text(
            label,
            style: TextStyle(
              fontSize: 12,
              color: _selectedIndex == index ? Colors.black : Colors.grey,
            ),
          ),
        ],
      ),
    );
  }




  Widget _buildTextField(String text) {
    return TextField(
      decoration: InputDecoration(
        filled: true,
        fillColor: Colors.grey[100],
        prefixIcon: const Icon(Icons.location_on, color: Colors.black54),
        hintText: text,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(8),
          borderSide: BorderSide.none,
        ),
      ),
    );
  }
}
